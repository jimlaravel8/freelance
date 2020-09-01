<?php

namespace Platform\Controllers\Business;

use Platform\Models\Business;
use Platform\Models\History;
use Platform\Models\Code;
use App\User;
use App\Http\Controllers;
use App\Notifications\NotificationDefault;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class BusinessesController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Businesses Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling business related
    | features.
    |
    */

    /**
     * Get business details for settings.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSettings(Request $request)
    {
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        return response()->json([
            'status' => 'success',
            'business' => auth()->user()->getBusiness()
        ], 200);
    }

    /**
     * Update settings.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postSettings(Request $request)
    {
        $locale = request('language', config('default.language'));
        app()->setLocale($locale);

        if (env('APP_DEMO', false) === true && (auth()->user()->id == 1 || auth()->user()->id == 2 || auth()->user()->id == 3)) {
            return response()->json(['status' => 'error', 'error' => "This is a demo account. You can't update or delete anything in this account. If you want to test this feature, sign up for a new account."], 422);
        }

        $v = Validator::make($request->all(), [
            'currency' => 'required|min:3|max:3',
            'name' => 'required|min:1|max:96',
            'short_description' => 'nullable|max:100',
            'cid' => 'exists:business_categories,id'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        // All good, update business
        $business = auth()->user()->business;

        $business->currency = $request->currency;
        $business->name = $request->name;
        $business->short_description = $request->short_description;
        $business->category_id = $request->cid;

        $business->save();

        // Update cover
        if (json_decode($request->cover_media_changed) === true) {
            $file = $request->file('cover');
            if ($file !== null) {
                $business
                    ->addMedia($file)
                    ->sanitizingFileName(function ($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection('cover', 'media');
            } else {
                $business
                    ->clearMediaCollection('cover');
            }
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Get wallet for user.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getWallet(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'wallet' => auth()->user()->getWallet()
        ], 200);
    }

    /**
     * Verify a customer number.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postVerifyCustomerNumber(Request $request)
    {
        // return $request->all();
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        $v = Validator::make($request->all(), [
            'customerNumber' => 'required|size:14'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $phone_no = str_replace('-', '', $request->customerNumber);

        $customer = User::where('whatsapp', $phone_no)->where('active', 1)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'errors' => ['customerNumber' => [trans('app.no_customer_found')]]
            ], 422);
        } else {
            return response()->json([
                'status' => 'success'
            ], 200);
        }
    }

    /**
     * Issue points with customer number.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postIssueAmount(Request $request)
    {
        // return $request->all();
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        $v = Validator::make($request->all(), [
            'customerNumber' => 'required|size:14',
            'amount' => 'required|between:1,100000'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $phone_no = str_replace('-', '', $request->customerNumber);

        $customer = User::where('whatsapp', $phone_no)->where('active', 1)->first();
        $business = auth()->user()->getBusiness(true);
        $businessName = $business['name'];
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'errors' => ['customerNumber' => [trans('app.no_customer_found')]]
            ], 422);
        } else {
            $amount = str_replace(',', '.', $request->amount);
            $points = (int) $amount * (int) $business['points_per_currency'];

            if (intval($points) < intval($business['min_points_per_purchase'])) $points = $business['min_points_per_purchase'];
            if (intval($points) > intval($business['max_points_per_purchase'])) $points = $business['max_points_per_purchase'];

            $history = new History;

            $history->user_id = $business['user_id'];
            $history->business_id = $business['business_id'];
            $history->customer_id = $customer->id;
            $history->customer_number = $customer->customer_number;
            $history->staff_id = auth()->user()->id;
            $history->staff_first_name = auth()->user()->first_name;
            $history->staff_last_name = auth()->user()->last_name;
            $history->staff_email = auth()->user()->email;
            $history->currency = $business['currency'];
            $history->currency_fraction_digits = $business['currency_fraction_digits'];
            $history->points = $points;
            $history->points_used = 0;
            $history->points_per_currency = $business['points_per_currency'];
            $history->point_value = $business['point_value'];
            $history->purchase_amount = intval($amount * intval(str_pad('1', $business['currency_fraction_digits'] + 1, '0')));
            $history->event = 'purchase';
            $history->expires_at = Carbon::now()->addMonths($business['points_expiration_months']);

            $history->save();
            // Send a mail to the customer.
            $email = new \stdClass();
            $email->to_name = $customer->name;
            $email->to_email = $customer->email;
            $email->subject = "You've earned points";
            $email->cta_label = "For more details check your Wallet";
            $email->cta_link = "https://piggybanksvg.com/customer/wallet";
            // $email->body_top = "Congrats $customer->name! You've got $points points from $businessName";
            $email->md_enabled = "points_issued";
            $email->points = $points;
            $email->customer_name = $customer->name;
            $email->business_name = $businessName;

            Mail::send(new \App\Mail\SendMail($email));
            $message = 'Thanks for supporting ' . $businessName . '. You earned ' . $points . ' point(s) ';

            $via = ['database'];
            Notification::send($customer, new NotificationDefault($customer, $message, $via));

            $badge = History::where('customer_id', auth()->user()->id)->count();
            if ($badge == 25) {
                $message = 'Congrats you have achieved Level ' . 1 .  ' status!';
                Notification::send($customer, new NotificationDefault($customer, $message, $via));
            } elseif ($badge == 50) {
                $message = 'Congrats you have achieved Level ' . 2 .  ' status!';
                Notification::send($customer, new NotificationDefault($customer, $message, $via));
            } elseif ($badge == 100) {
                $message = 'Congrats you have achieved Level ' . 3 .  ' status!';
                Notification::send($customer, new NotificationDefault($customer, $message, $via));
            } elseif ($badge == 200) {
                $message = 'Congrats you have achieved Level ' . 4 .  ' status!';
                Notification::send($customer, new NotificationDefault($customer, $message, $via));
            } elseif ($badge == 300) {
                $message = 'Congrats you have achieved Level ' . 5 .  ' status!';
                Notification::send($customer, new NotificationDefault($customer, $message, $via));
            }


            // Monthly transactions paid/unpaid..
            $exists = \DB::table('business_payment_transactions')->where('user_id', $business['id'])
                ->whereYear('month', Carbon::parse()->year)
                ->whereMonth('month', Carbon::parse()->month)
                ->first();
            if ($exists == null) {
                $latest = \DB::table('business_payment_transactions')->latest('id')->first()->id ?? 0;
                \DB::table('business_payment_transactions')->insert([
                    'user_id' => $business['id'],
                    'month' => Carbon::now(),
                    'paid' => 0,
                    'invoice_no' => 'PGB-#' . sprintf('%09d', ($latest + 1)),

                ]);
            }


            return response()->json([
                'status' => 'success',
                'points' => $points,
                'value' => $points * $business['point_value'],
                'customer_number' => $customer->whatsapp
            ], 200);
        }
    }

    /**
     * Verify a customer redemption code.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postVerifyRedemptionCode(Request $request)
    {
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        $v = Validator::make($request->all(), [
            'code' => 'required|digits:4'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $business = auth()->user()->getBusiness(true);
        // $businessName = $business['name'];
        $code = Code::where('business_id', $business['business_id'])->where('code', $request->code)->where('expires_at', '>=', Carbon::now())->first();
        $businessPoints = $code['points'];

        if (!$code) {
            return response()->json([
                'status' => 'error',
                'errors' => ['code' => [trans('app.code_not_found')]]
            ], 422);
        } else {
            $customer = User::where('id', $code->customer_id)->first();

            // $message = 'Congrats! You successfully redeemed ' . $businessPoints . ' points valued at ' . $business['currency'] . $businessPoints/100;

            // Notification::send($customer, new NotificationDefault($customer, $message));

            return response()->json([
                'status' => 'success',
                'points' => $code->points,
                'value' => $code->points * $business['point_value'],
                'currency' => $business['currency'],
                'customer_number' => $customer->whatsapp
            ], 200);
        }
    }

    /**
     * Process a customer redemption code.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postProcessRedemptionCode(Request $request)
    {
        // return $request->all();
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        $v = Validator::make($request->all(), [
            'code' => 'required|digits:4'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $business = auth()->user()->getBusiness(true);
        $code = Code::where('business_id', $business['business_id'])->where('code', $request->code)->where('expires_at', '>=', Carbon::now())->first();

        if (!$code) {
            return response()->json([
                'status' => 'error',
                'errors' => ['code' => [trans('app.code_not_found')]]
            ], 422);
        } else {
            //   dd('dwdw');
            // Deduct points
            $customer = User::where('id', $code->customer_id)->first();
            // dd($customer);
            $redeem = $customer->redeemPoints($code->points, $business['business_id']);

            if ($redeem['success']) {
                // Add history
                $history = new History;

                $history->user_id = $code->user_id;
                $history->business_id = $code->business_id;
                $history->customer_id = $customer->id;
                $history->customer_number = $customer->phone;
                //   $history->customer_number = $customer->customer_number;
                $history->staff_id = auth()->user()->id;
                $history->staff_first_name = auth()->user()->first_name;
                $history->staff_last_name = auth()->user()->last_name;
                $history->staff_email = auth()->user()->email;
                $history->points = -$code->points;
                $history->purchase_amount = intval($redeem['value'] * intval(str_pad('1', $business['currency_fraction_digits'] + 1, '0')));
                $history->event = 'discount';

                $history->save();


                // Send a mail to the customer.
                $email = new \stdClass();
                // $email->purchase_amount = (int) $code->points * (float) $business['point_value'] / (float) 2.67;
                $email->to_name = $customer->name;
                $email->to_email = $customer->email;
                $email->subject = "Points Redemption";
                $email->cta_label = "For more details check your Wallet";
                $email->cta_link = "https://piggybanksvg.com/customer/wallet";
                $email->md_enabled = "points_redeemed";
                $email->points = $code->points;
                $email->customer_name = $customer->name;
                $email->business_name = $business['name'];
                $email->purchase_amount = intval($code->points * $business['point_value']);
                Mail::send(new \App\Mail\SendMail($email));

                $businessPoints = $redeem['points'];
                $message = 'Congrats! You successfully redeemed ' . $businessPoints . ' points from ' . $business['name'] . ' valued at ' . $business['currency'] . $businessPoints / 100;
                $via = ['database'];
                Notification::send($customer, new NotificationDefault($customer, $message, $via));

                // Delete code
                $code->delete();
                $redeem['customer_number'] = $customer['whatsapp'];

                return response()->json([
                    'status' => 'success',
                    'redeem' => $redeem
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['code' => [$redeem['msg']]]
                ], 422);
            }
        }
    }

    /**
     * Update business rules.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUpdateRules(Request $request)
    {
        $locale = request('locale', config('default.language'));
        app()->setLocale($locale);

        if (!auth()->user()->premium) {
            return response()->json([
                'status' => 'success',
                'msg' => 'Premium required'
            ], 200);
        }

        $business = auth()->user()->getBusiness();

        $v = Validator::make($request->all(), [
            'points_expiration_months' => 'required|integer|between:1,60',
            'point_value' => 'required|numeric|min:' . $business['currency_point_value_step'] . '|max:' . $business['currency_point_value_max'],
            'points_per_currency' => 'required|integer|between:1,10000',
            'min_points_per_purchase' => 'required|integer|between:1,1000',
            'max_points_per_purchase' => 'required|integer|between:1,10000000',
            'min_points_per_redemption' => 'required|integer|between:1,10000000',
            'max_points_per_redemption' => 'required|integer|between:1,10000000'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $business = auth()->user()->business;

        $business->points_expiration_months = $request->points_expiration_months;
        $business->point_value = $request->point_value;
        $business->points_per_currency = $request->points_per_currency;
        $business->min_points_per_purchase = $request->min_points_per_purchase;
        $business->max_points_per_purchase = $request->max_points_per_purchase;
        $business->min_points_per_redemption = $request->min_points_per_redemption;
        $business->max_points_per_redemption = $request->max_points_per_redemption;

        $business->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Get transactions.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTransactions(Request $request)
    {
        $staff = auth()->user();
        $user = (auth()->user()->parent_id !== null) ? User::where('id', auth()->user()->parent_id)->first() : auth()->user();
        $query = \Platform\Models\History::with('customer')->where('user_id', $user->id)->orderBy('created_at', 'desc');
        if ($user->id != $staff->id) $query->where('staff_id', $staff->id);
        $transactions = $query->get();
        $business = auth()->user()->getBusiness(true);

        $transactions = $transactions->map(function ($transaction) use ($staff, $business) {
            return [
                // 'business_id' => $business['name'],
                'uuid' => $transaction->uuid,
                'customer_number' => $transaction->customer->whatsapp,
                //   'customer_number' => $transaction->customer_number,
                'event' => trans('app.' . $transaction->event),
                'points' => $transaction->points,
                'value' => $transaction->points * $business['point_value'],
                'staff_name' => $transaction->staff_first_name . ' ' . $transaction->staff_last_name,
                'staff_email' => $transaction->staff_email,
                'expires_at' => ($transaction->expires_at !== null) ? $transaction->expires_at->timezone($staff->getTimezone())->format('Y-m-d H:i:s') : null,
                'created_at' => $transaction->created_at->timezone($staff->getTimezone())->format('Y-m-d H:i:s'),
                'purchase_amount' => $transaction->purchase_amount,
            ];
        });

        return response()->json($transactions, 200);
    }

    /**
     * Get `active` businesses by `alphabetic order`.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBusinessesByAlphabeticOrder()
    {
        $businesses = Business::where('active', 1)
            ->leftJoin('users', 'businesses.created_by', 'user.id')
            ->orderBy('name')->get(['name']);
        return response()->json($businesses, 200);
    }

    /**
     * Return all transactions & total due grouped by month.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllTransactions()
    {
        $user = auth()->user();
        $query = \Platform\Models\History::where('user_id', $user->id)->orderBy('created_at', 'desc');
    }

    public function getStats(Request $request) {
        $stats = auth()->user()->getAdminStats();
        return response()->json($stats, 200);
    }

    public function getDailyPoints()
    {
        $earned = DB::table('history')->select(DB::raw('sum(points) as points_earned'))
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->where('points', '>', 0)
            ->get();

        $count_issuance = DB::table('history')
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->where('points', '>', 0)
            ->count();

        $count_redemption = DB::table('history')
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->where('points', '<', 0)
            ->count();


        $redeemed = DB::table('history')->select(DB::raw('ABS(sum(points)) as points_spent'))
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->where('points', '<', 0)
            ->get();
        return array_merge(
            $earned->toArray(),
            $redeemed->toArray(),
            ['total_businesses' => Business::count()],
            ['count_issuance' => $count_issuance],
            ['count_redemption' => $count_redemption]
        );
    }
}
