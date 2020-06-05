<?php namespace Platform\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class StripeController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Stripe Controller
   |--------------------------------------------------------------------------
   */

  /**
   * Create Stripe customer if necessary and subscribe to plan.
   */
  public function postToken(Request $request) {
    $stripe_plan_id = $request->stripe_plan_id;
    $months = $request->months;
    $token = $request->token;
    $email = $request->email;
    $type = $request->type;

    if ($token != null && auth()->check()) {
      $stripe_secret_key = config('default.stripe_secret_key');
      if ($stripe_secret_key === null) {
        abort(404);
      }

      // Set Stripe secret key
      \Stripe\Stripe::setApiKey($stripe_secret_key);

      $user = \App\User::where('id', auth()->user()->id)->first();
      app()->setLocale(substr($user->locale, 0, 2));

      if (! empty($user)) {
        // Customer exists?
        if ($user->remote_customer_id === null) {
          if ($user->previous_remote_customer_id !== null) {
            $stripe_customer_id = $user->previous_remote_customer_id;
            $user->remote_customer_id = $stripe_customer_id;
            $user->save();
          } else {
            // Create customer
            $customer = \Stripe\Customer::create(array(
              "description" => config('default.app_name') . ' - ' . $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ') - ID: ' . $user->id,
              "source" => $token,
              "email" => $email
            ));

            $stripe_customer_id = $customer->id;

            $user->remote_customer_id = $stripe_customer_id;
            $user->save();
          }
        } else {
          $stripe_customer_id = $user->remote_customer_id;
        }

        // Charge or subscribe
        if (Str::startsWith($stripe_plan_id, 'plan_')) { // Subscription
          $subscription = \Stripe\Subscription::create([
            'customer' => $stripe_customer_id, 
            'items' => [
              [
                'plan' => $stripe_plan_id
              ]
            ]
          ]);

          if (isset($subscription->plan)) {
            $expires = Carbon::now()->addMonths(1); // If an unknown billing interval is returned, add 1 month by default

            if (isset($subscription->plan->interval)) {
              $interval_count = (isset($subscription->plan->interval_count)) ? $subscription->plan->interval_count : 1;
              switch ($subscription->plan->interval) {
                case 'day': $expires = Carbon::now()->addDays($interval_count); break;
                case 'week': $expires = Carbon::now()->addWeeks($interval_count); break;
                case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
                case 'year': $expires = Carbon::now()->addYears($interval_count); break;
              }
            }

            $user->premium_expires_at = $expires;
            $user->save();
          }
        }
      }
    }
    return response()->json(['ok'], 200);
  }

  /**
   * Stripe webhook
   */
  public function postWebhook() {
    $sendMail = false;
    $sendUserMail = false;
    $stripe_secret_key = config('default.stripe_secret_key');
    if ($stripe_secret_key === null) {
      abort(404);
    }

    // Set Stripe secret key
    \Stripe\Stripe::setApiKey($stripe_secret_key);

    // Retrieve the request's body and parse it as JSON
    $input = @file_get_contents("php://input");
    $event_json = json_decode($input);

    // A customer is deleted in Stripe, set user to trial mode
    if ($event_json->type == 'customer.deleted') {
      $stripe_customer_id = $event_json->data->object->id;

      // Find matching user
      $user = \App\User::where('remote_customer_id', $stripe_customer_id)->first();
      app()->setLocale(substr($user->locale, 0, 2));

      if (! empty($user)) {
        $user->previous_remote_customer_id = $user->remote_customer_id;
        $user->remote_customer_id = null;
        $user->premium_expires_at = null;
        $user->save();

        $sendMail = true;
        $subject = "Customer deleted in Stripe dashboard";
        $body_top = "Customer: " . $user->name . " (" . $user->email . ")" . PHP_EOL . PHP_EOL;
        $body_top .= "User ID: " . $user->id . PHP_EOL . PHP_EOL;
        $body_top .= "Stripe Customer ID: " . $stripe_customer_id . PHP_EOL . PHP_EOL;
        $body_bottom = "";

        $sendUserMail = true;
        $user_subject = "Subscription cancelled";
        $user_body_top = "Your subscription has been cancelled." . PHP_EOL . PHP_EOL;
        $user_body_bottom = "";
      }
    }

    // The payment has succeeded, update the expiration date
    if ($event_json->type == 'invoice.payment_succeeded') {
      $stripe_customer_id = $event_json->data->object->customer;  
      $product_id_stripe = $event_json->data->object->lines->data{0}->plan->id;

      // Find matching user
      $user = \App\User::where('remote_customer_id', $stripe_customer_id)->first();
      app()->setLocale(substr($user->locale, 0, 2));

      // Find plan
      $plan = 'unkown';
      if ($product_id_stripe == config('default.stripe_plan_id_monthly')) $plan = trans('app.monthly_subscription');
      if ($product_id_stripe == config('default.stripe_plan_id_yearly')) $plan = trans('app.annual_subscription');

      if (! empty($user)) {
        $expires = Carbon::now()->addDays(1);

        if (isset($event_json->data->object->lines->data{0}->plan->interval)) {
          $interval_count = (isset($event_json->data->object->lines->data{0}->plan->interval_count)) ? $event_json->data->object->lines->data{0}->plan->interval_count : 1;
          switch ($event_json->data->object->lines->data{0}->plan->interval) {
            case 'day': $expires = Carbon::now()->addDays($interval_count); break;
            case 'week': $expires = Carbon::now()->addWeeks($interval_count); break;
            case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
            case 'year': $expires = Carbon::now()->addYears($interval_count); break;
          }
        }

        $user->premium_expires_at = $expires;
        $user->save();

        $sendMail = true;
        $subject = "Payment complete";
        $body_top = "Customer: " . $user->name . " (" . $user->email . ")" . PHP_EOL . PHP_EOL;
        $body_top .= "Plan: " . $plan . PHP_EOL . PHP_EOL;
        $body_top .= "User ID: " . $user->id . PHP_EOL . PHP_EOL;
        $body_top .= "Stripe Customer ID: " . $stripe_customer_id . PHP_EOL . PHP_EOL;
        $body_bottom = "";

        $sendUserMail = true;
        $user_subject = "Payment complete";
        $user_body_top = "Thank you for your purchase! Your subscription has been updated." . PHP_EOL . PHP_EOL;
        $user_body_bottom = "";
      }
    }

    // A subscription is updated for a customer in Stripe
    if ($event_json->type == 'customer.subscription.updated') {
      $stripe_customer_id = $event_json->data->object->customer;
      $product_id_stripe = $event_json->data->object->plan->id;
      $status = $event_json->data->object->status; // https://stripe.com/docs/api/subscriptions/object#subscription_object-status

      if ($status == 'active') {
        // Find matching user
        $user = \App\User::where('remote_customer_id', $stripe_customer_id)->first();
        app()->setLocale(substr($user->locale, 0, 2));

        // Find matching plan
        $plan = 'unkown';
        if ($product_id_stripe == config('default.stripe_plan_id_monthly')) $plan = trans('app.monthly_subscription');
        if ($product_id_stripe == config('default.stripe_plan_id_yearly')) $plan = trans('app.annual_subscription');

        if (! empty($user) && ! empty($plan)) {

          $expires = Carbon::now()->addMonths(1);

          if (isset($event_json->data->object->plan->interval)) {
            $interval_count = (isset($event_json->data->object->plan->interval_count)) ? $event_json->data->object->plan->interval_count : 1;
            switch ($event_json->data->object->plan->interval) {
              case 'day': $expires = Carbon::now()->addDays($interval_count); break;
              case 'week': $expires = Carbon::now()->addWeeks($interval_count); break;
              case 'month': $expires = Carbon::now()->addMonths($interval_count); break;
              case 'year': $expires = Carbon::now()->addYears($interval_count); break;
            }
          }

          $user->premium_expires_at = $expires;
          $user->save();

          $sendMail = true;
          $subject = trans('app.subscription_update');
          $body_top = "Customer: " . $user->name . " (" . $user->email . ")" . PHP_EOL . PHP_EOL;
          $body_top .= "Plan: " . $plan . PHP_EOL . PHP_EOL;
          $body_top .= "User ID: " . $user->id . PHP_EOL . PHP_EOL;
          $body_top .= "Stripe Customer ID: " . $stripe_customer_id . PHP_EOL . PHP_EOL;
          $body_bottom = "";

          $sendUserMail = true;
          $user_subject = trans('app.subscription_update');
          $user_body_top = "Your subscription has been successfully updated." . PHP_EOL . PHP_EOL;
          $user_body_bottom = "";
        }
      } elseif($status == 'unpaid' || $status == 'past_due') {
        // Find matching user
        $user = \App\User::where('remote_customer_id', $stripe_customer_id)->first();
        app()->setLocale(substr($user->locale, 0, 2));

        if (! empty($user)) {
          $user->premium_expires_at = Carbon::now()->addDays(config('system.premium_grace_period_days'));
          $user->save();

          $sendMail = true;
          $subject = trans('app.subscription_payment_failed_or_past_due');
          $body_top = "Customer: " . $user->name . " (" . $user->email . ")" . PHP_EOL . PHP_EOL;
          $body_top .= "Plan: " . $plan . PHP_EOL . PHP_EOL;
          $body_top .= "User ID: " . $user->id . PHP_EOL . PHP_EOL;
          $body_top .= "Stripe Customer ID: " . $stripe_customer_id . PHP_EOL . PHP_EOL;
          $body_bottom = "";

          $sendUserMail = true;
          $user_subject = trans('app.subscription_payment_failed_or_past_due');
          $user_body_top = trans('app.subscription_payment_failed_or_past_due_body') . PHP_EOL . PHP_EOL;
          $user_body_bottom = "";
        }
      }
    }

    // A subscription is deleted for a customer in Stripe, set user to trial mode
    // Deprecated for now because users can end subscription from system, and will keep active account until plan expires
    /*
    if ($event_json->type == 'customer.subscription.deleted') {
      $stripe_customer_id = $event_json->data->object->customer;

      // Find matching user
      $user = \App\User::where('remote_customer_id', $stripe_customer_id)->first();
      app()->setLocale(substr($user->locale, 0, 2));

      if (! empty($user)) {
        $user->premium_expires_at = null;
        $user->save();
      }
    }
    */

    if ($sendMail) {
      $admin = \App\User::where('role', 1)->first();
      app()->setLocale(substr($admin->locale, 0, 2));
      $email = new \stdClass;
      $email->to_name = $admin->name;
      $email->to_email = $admin->email;
      $email->subject = $subject;
      $email->body_top = $body_top;
      $email->cta_label = trans('app.visit_dashboard');
      $email->cta_url = config('default.app_url') . '/admin/users';
      $email->body_bottom = $body_bottom;

      Mail::send(new \App\Mail\SendMail($email));
    }

    if ($sendUserMail) {
      $email = new \stdClass;
      $email->to_name = $user->name;
      $email->to_email = $user->email;
      $email->subject = $user_subject;
      $email->body_top = $user_body_top;
      $email->cta_label = trans('app.visit_dashboard');
      $email->cta_url = config('default.app_url') . '/settings/business/subscription';
      $email->body_bottom = $user_body_bottom;

      Mail::send(new \App\Mail\SendMail($email));
    }
  }

  /**
   * Cancel subscription
   */
  public function postCancelSubscription() {
    $sendMail = false;
    $sendUserMail = false;
    $user = auth()->user();

    if ($user->remote_customer_id !== null) {
      // Get Stripe key
      $stripe_secret_key = config('default.stripe_secret_key');
      if ($stripe_secret_key === null) {
        return response()->json(['msg' => 'Stripe key not configured.']);
      }

      // Set Stripe secret key
      \Stripe\Stripe::setApiKey($stripe_secret_key);

      $customer = \Stripe\Customer::retrieve($user->remote_customer_id);

      $subscription = (isset($customer->subscriptions->data[0])) ? $customer->subscriptions->data[0] : null;

      if ($subscription !== null) {
        $subscription = \Stripe\Subscription::retrieve($subscription->id);
        $subscription->cancel();
        $user->previous_remote_customer_id = $user->remote_customer_id;
        $user->remote_customer_id = null;
        $user->save();

        $sendMail = true;
        $subject = trans('app.subscription_cancelled');
        $body_top = "Customer: " . $user->name . " (" . $user->email . ")" . PHP_EOL . PHP_EOL;
        $body_top .= "User ID: " . $user->id . PHP_EOL . PHP_EOL;
        $body_top .= "Stripe Customer ID: " . $user->remote_customer_id . PHP_EOL . PHP_EOL;
        $body_bottom = "";

        $sendUserMail = true;
        $user_subject = trans('app.subscription_cancelled');
        $user_body_top = trans('app.subscription_cancelled_body') . PHP_EOL . PHP_EOL;
        $user_body_bottom = "";

        if ($sendMail) {
          $admin = \App\User::where('role', 1)->first();
          app()->setLocale(substr($admin->locale, 0, 2));
          $email = new \stdClass;
          $email->to_name = $admin->name;
          $email->to_email = $admin->email;
          $email->subject = $subject;
          $email->body_top = $body_top;
          $email->cta_label = trans('app.visit_dashboard');
          $email->cta_url = config('default.app_url') . '/admin/users';
          $email->body_bottom = $body_bottom;

          Mail::send(new \App\Mail\SendMail($email));
        }

        if ($sendUserMail) {
          $email = new \stdClass;
          $email->to_name = $user->name;
          $email->to_email = $user->email;
          $email->subject = $user_subject;
          $email->body_top = $user_body_top;
          $email->cta_label = trans('app.visit_dashboard');
          $email->cta_url = config('default.app_url') . '/settings/business/subscription';
          $email->body_bottom = $user_body_bottom;

          Mail::send(new \App\Mail\SendMail($email));
        }

      } else {
        return response()->json(['msg' => 'User has no active subscription.']);
      }

      return response()->json(true);
    } else {
      return response()->json(['msg' => 'User has no remote customer id.']);
    }
  }
}