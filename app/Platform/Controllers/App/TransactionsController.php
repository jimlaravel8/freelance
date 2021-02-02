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

        # auth
        $auth = auth()->user();
        $business = Business::where('created_by', $auth->id)->first();
        # dates
        $month = Carbon::parse($date)->month;
        $year = Carbon::parse($date)->year;
        $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->/*endOfMonth()*/addDays('6')->format('Y-m-d');
        # transactions
        $transactions = \DB::table('business_payment_transactions')
        ->where('user_id', $business->id)
        ->whereMonth('month', $month) // 'month' actually isn't month, it's a datetime. 2020-01-01 10:00:00 etc.
        ->whereYear('month', $year) // 'month' actually isn't month, it's a datetime. 2020-01-01 10:00:00 etc.
        ->first();
        # if no transaction found for the given month & year, return null
        if($transactions == null) {
            return response()->json([
                'transactions' => [],
                'transaction_details' => [
                    'earnings' => 0,
                    'spendings' => 0,
                ],
                'due_details' => [
                    'date' => $nextMonthLast,
                    'amount' => 0,
                    'status' => 0
                ],
                'invoice_no' => null,
                'transaction_fee' => "0.1",
                "status_code" => 404
            ], 200);
        }
        // dd($business->id);
        # Get all of the records on the given month & year.
        $records = History::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('business_id', $business->id);
        # Prepare all of the records to return
        $transactions_all = $records->get()->map(function($data) {
            return [
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                'staff_name' => $data->staff_first_name . ' ' . $data->staff_last_name,
                'created_at' => $data->created_at->format('Y-m-d H:i:s')
            ];
        });
        # Total earnings, and counts.
        $earnings = History::where('business_id', $business->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('points', '>', 0);
        $earnings_return = [
          'amount' => $earnings->sum('points'),
          'count' => $earnings->count()
        ];

        # Total spendings, and counts.
        $spendings = History::where('business_id', $business->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('points', '<', 0);
        $spendings_return = [
            'amount' => $spendings->sum('points') * (int) (-1),
            'count' => $spendings->count()
        ];

        // $earnings = History::whereMonth('created_at', $month)
        // ->where('points', '>', 0)
        // ->select(\DB::raw('sum(points) as amount, COUNT(id) as count'))
        // ->where('user_id', $auth->id)
        // ->first();
        // $earnings = [
        //     'amount' => $earnings['amount'] ?? 0,
        //     'count' => $earnings['count']
        // ];
        //
        // $spendings = History::whereMonth('created_at', $month)
        // ->where('points', '<', 0)
        // ->select(\DB::raw('ABS(SUM(points)) as amount, COUNT(id) as count'))
        // ->where('user_id', $auth->id)
        // ->first();
        //
        // $spendings = [
        //     'amount' => $spendings['amount'] ?? 0,
        //     'count' => $spendings['count']
        // ];
        // return response()->json([
        //     'transactions' => $transactions,
        //     'transaction_details' => [
        //         'earnings' => $earnings,
        //         'spendings' => $spendings,
        //     ],
        //     'due_details' => [
        //         'date' => $nextMonthLast,
        //         'amount' => $business->currency . ' '.($auth->transaction_fee * ($earnings['count'] ?? 0)),
        //         'status' => $transactions->paid
        //     ]
        // ]);

        $total_sale = $earnings_return['amount'] + $spendings_return['amount'];

        return response()->json([
            'transactions' => $transactions_all,
            'transaction_details' => [
                'earnings' => $earnings_return,
                'spendings' => $spendings_return,
            ],
            'due_details' => [
                'date' => $nextMonthLast,
                'amount' => $business->currency . ' '.($auth->transaction_fee * ($earnings_return['count'] ?? 0)),
                'status' => $transactions->paid
            ],
            'invoice_no' => $transactions->invoice_no,
            'transaction_fee' => $auth->transaction_fee,
            'total_sale' => $total_sale
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
        # auth
        $auth = User::where('uuid', $uuid)->first();
        $business = Business::where('created_by', $auth->id)->first();

        # dates
        $month = Carbon::parse($date)->month;
        $year = Carbon::parse($date)->year;
        // $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->endOfMonth()->format('Y-m-d');
        $nextMonthLast = Carbon::parse($date)->addMonth()->copy()->addDays('6')->format('Y-m-d');
        # transactions
        $transactions = \DB::table('business_payment_transactions')
        ->where('user_id', $business->id)
        ->whereMonth('month', $month)
        ->whereYear('month', $year)
        ->first();

        if($transactions == null) {
            return response()->json([
                'transactions' => 0,
                'transaction_details' => [
                    'earnings' => 0,
                    'spendings' => 0,
                ],
                'due_details' => [
                    'date' => NULL,
                    'amount' => 0,
                    'status' => NULL
                ],
                "status_code" => 404
            ], 200);
        }

        $records = History::where('business_id', $business->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year);

        $transactions_return = $records->get()->map(function($data) {
            return [
                'customer_number' => $data->customer_number,
                'event' => trans('app.' . $data->event),
                'points' => $data->points,
                'staff_name' => $data->staff_first_name . ' ' . $data->staff_last_name,
                'created_at' => $data->created_at->format('Y-m-d H:i:s')
            ];
        });
        # Total earnings, and counts.
        $earnings = History::where('business_id', $business->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('points', '>', 0);
        $earnings_return = [
          'amount' => $earnings->sum('points'),
          'count' => $earnings->count()
        ];

        # Total spendings, and counts.
        $spendings = History::where('business_id', $business->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where('points', '<', 0);
        $spendings_return = [
            'amount' => $spendings->sum('points') * (int) (-1),
            'count' => $spendings->count()
        ];
        //
        // $earnings = History::whereMonth('created_at', $datex->month)
        // ->where('points', '>', 0)
        // ->select(\DB::raw('sum(points) as amount, COUNT(id) as count'))
        // ->where('user_id', $userid)
        // ->first();
        // $earnings = [
        //     'amount' => $earnings['amount'] ?? 0,
        //     'count' => $earnings['count']
        // ];
        // $spendings = History::whereMonth('created_at', $datex->month)
        // ->where('points', '<', 0)
        // ->select(\DB::raw('ABS(SUM(points)) as amount, COUNT(id) as count'))
        // ->where('user_id', $userid)
        // ->first();
        // $spendings = [
        //     'amount' => $spendings['amount'] ?? 0,
        //     'count' => $spendings['count']
        // ];

        return response()->json([
            'transactions' => $transactions_return,
            'transaction_details' => [
                'earnings' => $earnings_return,
                'spendings' => $spendings_return,
            ],
            'due_details' => [
                'date' => $nextMonthLast,
                'amount' => $business->currency . ' '.($auth->transaction_fee * ($earnings_return['count'] ?? 0)),
                'status' => $transactions->paid
            ],
            'invoice_no' => $transactions->invoice_no,
            'transaction_fee' => $auth->transaction_fee
        ]);
    }

    /**
     * Update payment status
     *
     */
    public function adminUpdatePaymentStatus($date, $uuid, $paid) {
        $userid = User::where('uuid', $uuid)->first()->id;
        $business = Business::where('created_by', $userid)->first();
        $datex = Carbon::parse($date);

        $transactions = \DB::table('business_payment_transactions')
        ->where('user_id', $business->id)
        ->whereMonth('month', $datex->month)
        ->whereYear('month', $datex->year)
        ->update([
            'paid' => $paid
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Changed the payment status of the selected month.'
        ]);

    }

    /**
     * Get available years for transactions history.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAvailableYears($uuid=null) {
        $admin = true;
        auth()->user()->role == 3 && $admin = false;

        $now = Carbon::now();
        $years = [];
        if(!$admin) { // if user is a business.
            // return auth()->user()->created_at;
            $create_year = Carbon::parse(auth()->user()->created_at)->year;
            $diff = $now->year - $create_year + 1;
            // return $diff = Carbon::parse(auth()->user()->created_at)->diffInYears($now);
            for($i=0; $i<$diff; $i++) {
                $year = Carbon::now()->subtract($i, 'years')->format('Y');
                array_push($years, $year);
            }
            return response()->json(['years' => $years]);
        }

        $user = User::where('uuid', $uuid)->first();
        $diff = Carbon::parse($user->created_at)->diffInYears($now) + 1;
        for($i=0; $i<$diff; $i++) {
            $year = Carbon::now()->subtract($i, 'years')->format('Y');
            array_push($years, $year);
        }
        return response()->json(['years' => $years]);
    }
}
