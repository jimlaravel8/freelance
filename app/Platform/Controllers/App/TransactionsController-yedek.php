<?php 

namespace Platform\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Platform\Models\History;
use App\User;
use Platform\Models\Business;
use Carbon\Carbon;
use Validator;

class TransactionsController extends \App\Http\Controllers\Controller {
    /**
     * Get transactions by months.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMonthlyTransactions($date) {
        $validator = Validator::make(['date' => $date], [
            'date' => 'date',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $auth = auth()->user();
        $business = User::whereId($auth->id)->first();
        $month = Carbon::parse($date)->month;
        $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->endOfMonth()->format('Y-m-d');
        $records = History::whereMonth('created_at', $month)->where('user_id', $auth->id);

        $records1 = $records->get()->map(function($data) {
            return [
                // 'uuid' => $data->uuid,
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                // 'value' => $data->purchase_amount / 100, // this line should be edited i guess.
                'staff_name' => $data->staff_first_name . ' ' . $data->staff_last_name,
                'created_at' => $data->created_at->format('Y-m-d H:i:s')
            ];
        });
        $records2 = History::whereMonth('created_at', $month)
        ->where('points', '>', 0)
        ->select(\DB::raw('sum(points) as amount, COUNT(id) as count'))
        ->first();

        $records3 = History::whereMonth('created_at', $month)
        ->where('points', '<', 0)
        ->select(\DB::raw('ABS(SUM(points)) as amount, COUNT(id) as count'))
        ->first();
        return response()->json([
            'transactions' => $records1, 
            'transaction_details' => [
                'earnings' => $records2,
                'spendings' => $records3,
            ],
            'due_details' => [
                'date' => $nextMonthLast,
                'amount' => $business->currency . ' '.($business->transaction_fee * ($records2->count ?? 0))
            ]
        ]);
    
    }
    /**
     * Get transactions by months for admin.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminGetMonthlyTransactions($date, $uuid) {
        $validator = Validator::make(['date' => $date, 'user_id' => $uuid], [
            'date' => 'date',
            'user_id' => 'exists:users,uuid'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $userid = User::where('uuid', $uuid)->first()->id;
        $business = Business::where('created_by', $userid)->first();
        $datex = Carbon::parse($date);
        $transactions = \DB::table('business_payment_transactions')
        ->where('user_id', $userid)
        ->whereMonth('month', $datex->month)
        ->whereYear('month', $datex->year)
        ->first();
        if($transactions == null) {
            return response()->json([
                'error' => 'No transaction found for this date',
                'code' => 400
            ], 400);
        }
        
        
        $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->endOfMonth()->format('Y-m-d');
        $records = History::whereMonth('created_at', $datex->month)->where('user_id', $business->id);

        $records1 = $records->get()->map(function($data) {
            return [
                // 'uuid' => $data->uuid,
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                // 'value' => $data->purchase_amount / 100, // this line should be edited i guess.
                'staff_name' => $data->staff_first_name . ' ' . $data->staff_last_name,
                'created_at' => $data->created_at->format('Y-m-d H:i:s')
            ];
        });
        $records2 = History::whereMonth('created_at', $datex->month)
        ->where('points', '>', 0)
        ->select(\DB::raw('sum(points) as amount, COUNT(id) as count'))
        ->first();

        $records3 = History::whereMonth('created_at', $datex->month)
        ->where('points', '<', 0)
        ->select(\DB::raw('ABS(SUM(points)) as amount, COUNT(id) as count'))
        ->first();
        return response()->json([
            'transactions' => $records1, 
            'transaction_details' => [
                'earnings' => $records2,
                'spendings' => $records3,
            ],
            'due_details' => [
                'date' => $nextMonthLast,
                'amount' => $business->currency . ' '.($business->transaction_fee * ($records2->count ?? 0)),
                'status' => $transactions->paid == 0 ? 'Not Paid' : 'Paid'
            ]
        ]);
    }

    /**
     * Update payment status
     * 
     * 
     */
    public function adminUpdatePaymentStatus($date, $uuid, $paid) {
        $userid = User::where('uuid', $uuid)->first()->id;
        $business = Business::where('created_by', $userid)->first();
        $datex = Carbon::parse($date);
        
        $transactions = \DB::table('business_payment_transactions')
        ->where('user_id', $userid)
        ->whereMonth('month', $datex->month)
        ->whereYear('month', $datex->year)
        ->update([
            'paid' => $paid
        ]);
    }
}