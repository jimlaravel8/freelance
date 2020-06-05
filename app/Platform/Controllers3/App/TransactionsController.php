<?php 

namespace Platform\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Platform\Models\History;
use Platform\Models\Business;
use Carbon\Carbon;
use Validator;

class TransactionsController extends \App\Http\Controllers\Controller {
    /**
     * Get current month's transactions.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCurrentMonthTransactions() {
    $records = History::where('created_at', '>', Carbon::now()->subMonth())->get();
        $records = $records->map(function($data) {
            return [
                'uuid' => $data->uuid,
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                'value' => $data->purchase_amount / 100, // this line should be edited i guess.
                'staff_name' => $data->staff_first_name . ' ' . $data->staff_last_name,
                'created_at' => $data->created_at->format('Y-m-d H:i:s')
                ];
            });
            return response()->json($records);
        }

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
        $business = Business::where('created_by', $auth->id)->first();
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
                'amount' => $business->currency . ' '.($business->transaction_fee * $records2->count)
            ]
        ]);
    
    }
    /**
     * Get transactions by months for admin.
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminGetMonthlyTransactions($userid, $date) {
        $validator = Validator::make(['date' => $date, 'userid' => $userid], [
            'date' => 'date',
            'userid' => 'exists:users,id'
        ], [
            'userid.exists' => 'Invalid user.'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $business = Business::where('created_by', $userid)->first();
        $month = Carbon::parse($date)->month;
        $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->endOfMonth();
        $records = History::whereMonth('created_at', $month);

        $records1 = $records->get()->map(function($data) {
            return [
                'uuid' => $data->uuid,
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                'value' => $data->purchase_amount / 100, // this line should be edited i guess.
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
                'date' => $nextMonthLast->format('Y-m-d H:i:s'),
                'amount' => (int)($business->transaction_fee * $records3->count)
            ]
        ]);
    }
}