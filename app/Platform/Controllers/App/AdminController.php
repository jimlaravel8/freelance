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
use Platform\Models\Business;

class AdminController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    */

    /**
     * Get admin stats.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStats(Request $request) {
        $stats = auth()->user()->getAdminStats('7days');
        return response()->json($stats, 200);
    }

    public function statsfilter(Request $request) {
        // return $request->all();
        $date_range = $request->date;
        $stats = auth()->user()->getAdminStats($date_range);
        return response()->json($stats, 200);
    }
    /**
     * Get branding data.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBranding(Request $request) {
        $user = auth()->user();

        $branding = [
          'payment_provider' => config('general.payment_provider'),
          'payment_test_mode' => config('general.payment_test_mode'),
          'app_name' => $user->app_name,
          'app_contact' => $user->app_contact,
          'app_mail_name_from' => $user->app_mail_name_from,
          'app_mail_address_from' => $user->app_mail_address_from,
          'app_host' => $user->app_host,
          'account_host' => config('general.cname_domain')
        ];

        return response()->json($branding, 200);
    }

    /**
     * Update branding.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUpdateBranding(Request $request) {
        if (auth()->user()->app_demo == 1) return;

        if (env('APP_DEMO', false) === true && (auth()->user()->id == 1 || auth()->user()->id == 2)) {
          return;
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Get existing business categories
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllCategories() {
        $categories = \DB::table('business_categories')->get(['id', 'name', 'short_description']);
        return $categories ?? 'No categories found.';
    }

    /**
     * Get a specific category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCategory($id) {
        $category = \DB::table('business_categories')->where('id', $id)->get(['id', 'name', 'short_description']);
        return $category;
    }

    /**
     * Create a new category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createNewCategory(Request $request) {
        $request->validate([
            'name' => 'required|unique:business_categories,name|max:250'
        ]);

        $inserted = \DB::table('business_categories')->insert([
            'name' => $request->name
        ]);

        return response()->json([ 'message' =>
            $inserted ?
            'You\'ve successfully created a new category.':
            'An error occured while creating a category'
        ]);
    }

    /**
     * Update an existing category.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateExistingCategory(Request $request, $id) {
        $request->validate([
            'name' => 'required|max:250'
        ]);

        $updated = \DB::table('business_categories')->where('id', $id)->update([
            'name' => $request->name
        ]);

        return response()->json(['message' =>
            $updated >= 1 ?
            'Updated the category successfully.' :
            'Nothing were updated.'
        ]);
    }

    /**
     * Delete an existing category.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAnExistingCategory(Request $request, $id) {
        $deleted = \DB::table('business_categories')->whereId($id)->delete();

        return response()->json([ 'message' =>
            $deleted >= 1 ?
            'Deleted the category successfully.' :
            'Nothing were deleted.'
        ]);
    }

    public function business_report(Request $request)
    {
        $date_arr = $request->date;
        $businesses = Business::with('histories')->get();
        $businesses->transform(function($business) {
            $billings = 0;
            foreach ($business->histories as $key => $value) {
                // dd($value['points']);
                $billings += $value['points'] * $business['point_value'];
            }
            $business->billings = $billings;
            return $business;
        });
        return $businesses;
    }
}

