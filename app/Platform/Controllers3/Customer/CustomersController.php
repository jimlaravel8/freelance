<?php

namespace Platform\Controllers\Customer;

use Platform\Models\Business;
use Platform\Models\History;
use Platform\Models\Code;
use Platform\Controllers\Core;
use App\User;
use App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CustomersController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Customers Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling customer related
    | features. 
    |
    */

    /**
     * Get wallet for user.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getWallet(Request $request) {
      return response()->json([
        'status' => 'success',
        'wallet' => auth()->user()->getWallet()
      ], 200);
    }

    /**
     * Get card from wallet for user.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getCard(Request $request) {
      $uuid = $request->uuid;

      return response()->json([
        'status' => 'success',
        'card' => auth()->user()->getCard($uuid)
      ], 200);
    }

    /**
     * Generate 4-digit discount code.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function postGenerateDiscountCode(Request $request) {
      $uuid = $request->uuid;

      // Get business with rules based on customer and business account,
      // without history
      $business = auth()->user()->getCard($uuid, false, true);

      if ($business === null) {
        return response()->json([
          'status' => 'error',
          'errors' => ['amount' => [trans('app.no_business_found')]]
        ], 422);
      }

      $v = Validator::make($request->all(), [
        'amount' => 'required|numeric|between:' . $business['balance']['rules']['min_amount_redeemable'] . ',' . $business['balance']['rules']['max_amount_redeemable']
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      /**
      * Passed all validation
      */

      // Delete existing codes
      $customer_id = auth()->user()->id;
      $deletedRows = Code::where('customer_id', $customer_id)->delete();

      // Add generated code to codes table
      $token = $this->getUniqueCode($customer_id, $business['business_id']);
      $amount = round(floor($request->amount * 100) / 100, 2);
      $points = $amount / $business['balance']['rules']['point_value'];
      $expires_at = Carbon::now()->addHours(config('default.discount_code_expiration_time_hours'));

      $code = new Code;
      $code->user_id = $business['user_id'];
      $code->business_id = $business['business_id'];
      $code->customer_id = $customer_id;
      $code->code = $token;
      $code->points = $points;
      $code->expires_at = $expires_at;

      $code->save();

      return response()->json([
        'status' => 'success',
        'code' => $token,
        'points' => $points,
        'amount' => $amount,
        'expires_at' => $expires_at->timezone(auth()->user()->getTimezone())->format('Y-m-d H:i:s'),
        'name' => $business['name']
      ], 200);
    }

    /**
     * Make sure code is unique
     *
     * @return 4-digit code
     */
    public function getUniqueCode($customer_id, $business_id) {
      $token = Core\Secure::getRandom(4, '1234567890');

      $code = Code::where('customer_id', $customer_id)->where('business_id', $business_id)->where('code', $token)->first();

      if (!$code) {
        return $token;
      } else {
        return $this->getUniqueCode($customer_id, $business_id);
      }
    }
  }