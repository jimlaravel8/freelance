<?php

namespace Platform\Controllers\App;

use App\User;
use Platform\Models\Business;
use Platform\Models\History;
use Platform\Models\Code;
use App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StatisticsController extends \App\Http\Controllers\Controller {
    /** DO NOT USE!
     * Get daily earned & redeemed coins by hour.
     * /admin/dailypoints
     * 
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getDailyPointsByHours() {
        $points = DB::table('history')->select(DB::raw('sum(points) as points_earned, TIME_FORMAT(created_at, "%H:00") as hour'))
        ->whereDate('created_at', Carbon::now()->toDateString())
        ->where('points', '>', 0)
        ->groupBy('hour')
        ->get();

        $points2 = DB::table('history')->select(DB::raw('ABS(sum(points)) as points_spent, TIME_FORMAT(created_at, "%H:00") as hour'))
        ->whereDate('created_at', Carbon::now()->toDateString())
        ->where('points', '<', 0)
        ->groupBy('hour')
        ->get();
        $data = array_merge($points->toArray(), $points2->toArray(), ['total_businesses' => Business::count()]);
        return response()->json($data);
    }
    
    /** temp
     * Get daily earned & redeemed coins by 24 hours.
     * 
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getDailyPoints(){
        $earned = DB::table('history')->select(DB::raw('sum(points) as points_earned'))
        ->whereDate('created_at', Carbon::now()->toDateString())
        ->where('points', '>', 0)
        ->get();
        $redeemed = DB::table('history')->select(DB::raw('ABS(sum(points)) as points_spent'))
        ->whereDate('created_at', Carbon::now()->toDateString())
        ->where('points', '<', 0)
        ->get();
        return array_merge($earned->toArray(), $redeemed->toArray(), ['total_businesses' => Business::count()]);
    }

    /**
     * Get total customers
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTotalCustomers() {
        $customers = User::where('role', 2)->get();
        $customersCount = $customers->count();

        return response()->json([
                'customerList' => $customers,
                'customerCount' => $customersCount
                ], 200);
    }

    /**
     * Get total businesses
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTotalBusinesses() {
        $businesses = Business::where('active', 1)->get(['name']);
        return response()->json($businesses);
    }

    /**
     * Get businesses list
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBusinessesList() {
        // $data = Business::where('active', 1)->get(['category_id as cid', 'name', 'short_description', 'whatsapp as phone']);
        $data = Business::join('users', 'businesses.created_by', 'users.id')
        ->where('users.active', 1)
        ->get(['category_id as cid', 'businesses.name as name', 'short_description', 'businesses.phone as phone', 'users.location as location']);
        return response()->json($data);
    }
}