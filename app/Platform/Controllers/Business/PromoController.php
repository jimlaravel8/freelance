<?php

namespace Platform\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Platform\Models\Business;
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
         $promos = Promo::with(['business', 'user'])->get();
        $promos->transform(function($promo) {
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
        $promo = new Promo;
        $promo->promo_message = $request->promo_message;
        $business = Business::where('name', auth()->user()->business_name)->first('id');
        $promo->business_id = $business->id;
        $promo->user_id = auth()->user()->id;
        $promo->save();

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
