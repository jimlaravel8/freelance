<?php

namespace Platform\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Notifications\NotificationDefault;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Platform\Models\Business;
use Platform\Models\History;
use Platform\Models\Promo;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id = auth()->user()->getBusiness()['id'];
        $promos = Promo::with(['business', 'user'])->where('business_id', $business_id)->get();
        $promos->transform(function ($promo) {
            $promo->user_name = $promo->user->name;
            $promo->user_name = $promo->business->name;
            return $promo;
        });
        return $promos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return auth()->user();
        // return $request->all();
        $customers = History::select(DB::raw('count(*) as no_count, customer_id'))->groupBy('customer_id')->get();
        $promo = new Promo;
        $promo->promo_message = $request->promo_message;
        $business = Business::where('name', auth()->user()->business_name)->first('id');
        $promo->business_id = $business->id;
        $promo->user_id = auth()->user()->id;

        $via = ['database'];
        $customer_count = 0;
        // $icon = ;
        foreach ($customers as $value) {
            $message = 'Message from: ' . auth()->user()->business_name . ' ' . $request->promo_message;
            $user = User::find($value['customer_id']);
            // return $value['no_count'];
            if ($value['no_count'] > 1) {
                $customer_count += 1;

                $subject = 'Promotion';
                Notification::send($user, new NotificationDefault($user, $message, $via, $subject));
            }
        }
        $promo->customer_count = $customer_count;
        $promo->save();

        // return $customers = History::where('business_id', $business->id)->distinct('customer_id')->get('customer_id');


        return response()->json([
            'status' => 'success',
            'data' => $promo
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promo $promo)
    {
        //
    }
}
