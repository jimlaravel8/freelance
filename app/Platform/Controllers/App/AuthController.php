<?php

namespace Platform\Controllers\App;

use App\User;
use Platform\Models\Business;
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
use App\Mail\SendMail;

class AuthController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Authorization Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling authentication related
    | features like registration, login, logout and password reset.
    | It's designed for /api/ use with JSON responses.
    |
    */

    /**
     * Handle user registration.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function register(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $type = request('type', 'customer');
      $role = 2;

      if ($type == 'customer') {
        $v = Validator::make($request->all(), [
          'first_name' => 'required|min:1|max:32',
          'last_name' => 'required|min:1|max:32',
          'email' => 'required|email|max:64|unique:users',
          'password' => 'required|min:8|max:24',
          'whatsapp' => 'required|regex:/\D*([2-9]\d{2})(\D*)([2-9]\d{2})(\D*)(\d{4})\D*/|min:10'
        ], [
          'whatsapp.regex' => 'A correct phone number should look like this: 1 212-222-1010'
        ]);
      } elseif ($type == 'business') {
        $role = 3;
        $v = Validator::make($request->all(), [
          'currency' => 'required|min:3|max:3',
          'business_name' => 'required|min:2|max:32',
          'first_name' => 'required|min:1|max:32',
          'last_name' => 'required|min:1|max:32',
          'email' => 'required|email|max:64|unique:users',
          'password' => 'required|min:5|max:24',
          // 'whatsapp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
          'phone' => 'required|regex:/\D*([2-9]\d{2})(\D*)([2-9]\d{2})(\D*)(\d{4})\D*/',
          'cid' => 'required|exists:business_categories,id'
        ], [
          'phone.regex' => 'A correct phone number should look like this: 1 212-222-1010'
        ]);
      }

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      // Detect currency based on locale
      $currency = config('default.currency');
      if (false !== setlocale(LC_ALL, $locale)) {
        $locale_info = localeconv();
        // $currency = $locale_info['int_curr_symbol'];
      }

      $language = ($request->language !== null) ? $request->language : config('default.language');
      $timezone = ($request->timezone !== null) ? $request->timezone : config('default.timezone');
      // $currency = request('currency', $currency);
      $user = new User;
      $user->role = $role;
      $user->active = 1;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->email = $request->email;
      $user->whatsapp = $request->whatsapp;
      $user->password = bcrypt($request->password);
      $user->language = $language;
      $user->locale = str_replace('-', '_', $locale);
      $user->timezone = $timezone;
      $user->currency = $currency;
      $user->last_ip_address = request()->ip();
      $user->created_by = 1;
      $user->location = $request->location;
      if($type == 'business'){
        $user->premium_expires_at = date(Carbon::now()->addCentury());
      }

      $user->save();
      if($type == 'customer') {
        // Send mail to the customer.
        // Mail::to($user)->send(new SendMail([
        //   'to_name' => $request->first_name,
        //   'to_email' => $request->email,
        //   'subject' => ''        
        // ]));
        
        // Send mail to the customer.
        $email = new \stdClass;
        $email->locale = "en";
        $email->to_name = $request->first_name;
        $email->to_email = $request->email;
        $email->subject = trans('mail.registration_welcome_mail_subject');
        $email->body_top = trans('mail.registration_welcome_mail_top');
        Mail::send(new \App\Mail\SendMail($email));
      }

      if ($type == 'business') {
        // Add business
        $business = new Business;
        $business->name = $request->business_name;
        $business->currency = $request->currency;
        $business->created_by = $user->id;
        $business->category_id = $request->cid;
        $business->phone = $request->phone;
        
        // Business point rules
        $business->points_expiration_months = (int) 60;
        $business->point_value = (float) 0.01;
        $business->points_per_currency = (int) 1;
        $business->min_points_per_purchase = (int) 1;
        $business->max_points_per_purchase = (int) 10000;
        $business->min_points_per_redemption = (int) 500;
        $business->max_points_per_redemption = (int) 5000;
        
        $business->save();
      }
      return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle user login.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function login(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'email' => 'required|email|max:64',
        'password' => 'required|min:6|max:24'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $remember = (bool) $request->get('remember', false);

      $credentials = $request->only('email', 'password');
      $credentials['active'] = 1;

      if ($token = $this->guard()->attempt($credentials, $remember)) {
        auth()->user()->logins = auth()->user()->logins + 1;
        auth()->user()->last_ip_address =  request()->ip();
        auth()->user()->last_login = Carbon::now('UTC');
        auth()->user()->save();

        return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
      }
      return response()->json(['error' => 'login_error'], 401);
    }

    /**
     * Handle impersonate login.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function impersonate(Request $request) {
      $uuid = request('uuid', null);
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      if (auth()->user()->role == 1) {
        $user = User::withoutGlobalScopes()->where('uuid', $uuid)->firstOrFail();
        if ($token = $this->guard()->login($user)) {
          return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
      } elseif (auth()->user()->role == 3) {
        $user = User::withoutGlobalScopes()->where('uuid', $uuid)->where('created_by', auth()->user()->id)->firstOrFail();
        if ($token = $this->guard()->login($user)) {
          return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
      }

      return response()->json(['error' => 'login_error'], 401);
    }

    /**
     * Handle user logout.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function logout() {
      $this->guard()->logout();
      return response()->json([
        'status' => 'success'
      ], 200);
    }

    /**
     * Refresh authorization token.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function refresh() {
      try {
        $token = $this->guard()->refresh();
      }
      catch (\Exception $e) {
        return response()->json(['error' => 'refresh_token_error'], 401);
      }

      return response()
        ->json(['status' => 'successs'], 200)
        ->header('Authorization', $token);
    }

    /**
     * Send a password reset email.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordReset(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'email' => 'required|email|max:64'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $user = User::withoutGlobalScopes()->where('email', $request->email)
        ->where('active', 1)
        ->first();

      if ($user !== null) {

        $token = Str::random(32);

        DB::table('password_resets')
          ->where('email', $user->email)
          ->delete();

        DB::table('password_resets')->insert(
          ['email' => $user->email, 'token' => $token, 'created_at' => Carbon::now('UTC')]
        );

        $email = new \stdClass;
        $email->locale = $locale;
        $email->to_name = $user->name;
        $email->to_email = $user->email;
        $email->subject = trans('mail.reset_password_mail_subject');
        $email->body_top = trans('mail.reset_password_mail_top');
        $email->cta_label = trans('mail.reset_password_mail_cta');
        $email->cta_url = url('password/reset/' . $token);
        $email->body_bottom = trans('mail.reset_password_mail_bottom');

        Mail::send(new \App\Mail\SendMail($email));

      } else {
        return response()->json([
          'status' => 'error',
          'error' => trans('passwords.user')
        ], 200);
      }

      return response()->json([
        'status' => 'success'
      ], 200);
    }

    /**
     * Validate reset password token.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordResetValidateToken(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'token' => 'required|min:32|max:32'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $password_reset = DB::table('password_resets')
        ->select('email')
        ->where('token', $request->token)
        ->where('created_at', '>=', \Carbon\Carbon::now()->addHour(-24)->toDateTimeString())
        ->first();

      if ($password_reset !== null) {
        return response()->json([
          'status' => 'success'
        ], 200);
      } else {
        return response()->json([
          'status' => 'error',
          'error' => 'invalid_token'
        ], 200);
      }
    }

    /**
     * Update password.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordUpdate(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'token' => 'required|min:32|max:32',
        'password' => 'required|min:8|max:24'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $password_reset = DB::table('password_resets')
        ->select('email')
        ->where('token', $request->token)
        ->where('created_at', '>=', \Carbon\Carbon::now()->addHour(-24)->toDateTimeString())
        ->first();

      if ($password_reset !== null) {

        DB::table('password_resets')->where('token', $request->token)->delete();

        $user = User::withoutGlobalScopes()->where('email', $password_reset->email)
          ->where('active', 1)
          ->first();

        if ($user !== null) {

          $user->password = bcrypt($request->password);
          $user->save();

          return response()->json([
            'status' => 'success'
          ], 200);
        }
      } else {
        return response()->json([
          'status' => 'error',
          'error' => 'invalid_token'
        ], 200);
      }
    }

    /**
     * Update profile.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function postUpdateProfile(Request $request) {
      $locale = request('language', config('default.language'));
      app()->setLocale($locale);

      if (env('APP_DEMO', false) === true && (auth()->user()->id == 1 || auth()->user()->id == 2 || auth()->user()->id == 3)) {
        return response()->json(['status' => 'error', 'error' => 'demo'], 422);
      }

      $v = Validator::make($request->all(), [
        'current_password' => 'required|min:8|max:24',
        'first_name' => 'required|min:1|max:32',
        'last_name' => 'required|min:1|max:32',
        'email' => ['required', 'email', 'max:64', Rule::unique('users')->where(function ($query) {
            return $query->where('id', '<>', auth()->user()->id);
          })
        ],
        'whatsapp' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      // Verify password
      if (! Hash::check($request->current_password, auth()->user()->password)) {
        return response()->json([
          'status' => 'error',
          'errors' => ['current_password' => [trans('app.current_password_incorrect')]]
        ], 422);
      }

      // All good, update profile
      auth()->user()->first_name = $request->first_name;
      auth()->user()->last_name = $request->last_name;
      auth()->user()->email = $request->email;
      auth()->user()->whatsapp = $request->whatsapp;
      // auth()->user()->timezone = $request->timezone;
      auth()->user()->location = $request->location;
      auth()->user()->locale = $request->locale;
      auth()->user()->currency = $request->currency;
      auth()->user()->language = substr($locale, 0, 2);

      // Update password
      if ($request->new_password !== null && $request->new_password != 'null') auth()->user()->password = bcrypt($request->new_password);

      auth()->user()->save();

      // Update avatar
      if (json_decode($request->avatar_media_changed) === true) {
        $file = $request->file('avatar');
        if ($file !== null) {
          auth()->user()
            ->addMedia($file)
            ->sanitizingFileName(function($fileName) {
              return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('avatar', 'media');
        } else {
          auth()->user()
            ->clearMediaCollection('avatar');
        }
      }

      // Update cover
      if (json_decode($request->cover_media_changed) === true) {
        $file = $request->file('cover');
        if ($file !== null) {
          auth()->user()
            ->addMedia($file)
            ->sanitizingFileName(function($fileName) {
              return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('cover', 'media');
        } else {
          auth()->user()
            ->clearMediaCollection('cover');
        }
      }

      return response()->json([
        'status' => 'success',
        'user' => $this->user(false)
      ], 200);
    }

    /**
     * Get user info.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public static function user($returnResponse = true) {
      $user = User::withoutGlobalScopes()->where('id', Auth::user()->id)->where('active', 1)->firstOrFail();

      $return = [
        'customer_number' => $user->customer_number,
        'active' => (boolean) $user->active,
        'premium' => (boolean) $user->premium,
        'avatar' => $user->avatar,
        'first_name' => $user->first_name ?? '',
        'last_name' => $user->last_name ?? '',
        'email' => $user->email,
        'whatsapp' => $user->whatsapp ?? '',
        'role' => (int) $user->role,
        'language' => $user->getLanguage(),
        'locale' => $user->getLocale(),
        'location' => $user->location,
        'timezone' => $user->getTimezone(),
        'currency' => $user->getCurrency()
      ];

      if ($returnResponse) {
        return response()->json([
          'status' => 'success',
          'data' => $return
        ], 200);
      } else {
        return $return;
      }
    }

    /**
     * Get guard for logged in user.
     *
     * @return \Illuminate\Support\Facades\Auth 
     */
    private function guard() {
      return Auth::guard('api');
    }
}