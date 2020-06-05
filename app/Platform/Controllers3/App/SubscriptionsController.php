<?php

namespace Platform\Controllers\App;

use App\User;
use App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SubscriptionsController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subscriptions Controller
    |--------------------------------------------------------------------------
    */

    /**
     * Get subscription settings.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getSettings(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);


      $key = '';
      $plan_id_monthly = '';
      $plan_id_yearly = '';

      if (config('default.payment_gateway') == 'stripe') {
        $key = config('default.stripe_public_key');
        $plan_id_monthly = config('default.stripe_plan_id_monthly');
        $plan_id_yearly = config('default.stripe_plan_id_yearly');
      }
      $subscriptionSettings = [
        'premium_price_monthly' => config('default.premium_price_monthly'),
        'premium_price_yearly' => config('default.premium_price_yearly'),
        'premium_price_currency' => config('default.premium_price_currency'),
        'gateway' => config('default.payment_gateway'),
        'key' => $key,
        'plan_id_monthly' => $plan_id_monthly,
        'plan_id_yearly' => $plan_id_yearly,
        'expires_at' => (auth()->user()->premium_expires_at !== null) ? auth()->user()->premium_expires_at->timezone(auth()->user()->getTimezone())->format('Y-m-d H:i:s') : null,
        'remote_customer_id' => auth()->user()->remote_customer_id,
        'email' => config('default.app_contact_email')
      ];
      
      return response()->json($subscriptionSettings, 200);
  }

  /**
   * Process demo subscription expiration period.
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function postProcessDemo(Request $request) {
    $locale = request('locale', config('default.language'));
    app()->setLocale($locale);

    if (!env('APP_DEMO', false)) {
      return response()->json(['status' => 'error', 'msg' => "Demo mode required"], 422);
    }

    $v = Validator::make($request->all(), [
      'months' => 'required|integer'
    ]);

    if ($v->fails()) {
      return response()->json([
        'status' => 'error',
        'errors' => $v->errors()
      ], 422);
    }

    $premium_expires_at = Carbon::now()->addMonths($request->months);

    auth()->user()->premium_expires_at = $premium_expires_at;
    auth()->user()->save();

    return response()->json([
      'status' => 'success',
      'user' => \Platform\Controllers\App\AuthController::user(false)
    ], 200);
  }
}