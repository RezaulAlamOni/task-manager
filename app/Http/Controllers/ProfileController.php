<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $profile = User::select('id', 'name', 'phone')->find(Auth::id())->makeVisible('phone');
        return $profile;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCardInfo()
    {
        $cardInfo = User::select('id', 'card_brand', 'card_last_four', 'card_country')->find(Auth::id())
        ->makeVisible(['card_brand', 'card_last_four', 'card_country']);
        return response()->json(['success' => true, 'data' => $cardInfo, 'status' => 200], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showBillingInfo()
    {
        $billingInfo = User::select('id', 'billing_address', 'billing_address_line_2', 'billing_city', 'billing_state', 'billing_zip', 'billing_country', 'vat_id','extra_billing_information')->find(Auth::id())
        ->makeVisible(['billing_address', 'billing_address_line_2', 'billing_city', 'billing_zip', 'billing_country', 'extra_billing_information']);
        return response()->json(['success' => true, 'data' => $billingInfo, 'status' => 200], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors(), 'status' => 400], Response::HTTP_OK);
        }

        $user = User::find(Auth::id());

        $updated = $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
        if ($updated) {
            return response()->json(['success' => true, 'data' => $updated, 'status' => 200], Response::HTTP_OK);
        } else {
            return response()->json(['success' => false, 'data' => 'Profile Not Updated', 'status' => 401], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCardInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_brand' => 'required|max:255',
            'card_last_four' => 'required|numeric',
            'card_country' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors(), 'status' => 400], Response::HTTP_OK);
        }

        $user = User::find(Auth::id());

        $updated = $user->update([
            'card_brand' => $request->card_brand,
            'card_last_four' => $request->card_last_four,
            'card_country' => $request->card_country,
        ]);
        if ($updated) {
            return response()->json(['success' => true, 'data' => $updated, 'status' => 200], Response::HTTP_OK);
        } else {
            return response()->json(['success' => false, 'data' => 'Card Info Not Updated', 'status' => 401], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateBillingInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_address' => 'required|max:255',
            'billing_city' => 'required|max:255',
            'billing_state' => 'required|max:255',
            'billing_zip' => 'required|numeric',
            'billing_country' => 'required|max:255',
            'vat_id' => 'required|max:255',
            'extra_billing_information' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors(), 'status' => 400], Response::HTTP_OK);
        }

        $user = User::find(Auth::id());

        $updated = $user->update([
            'billing_address' => $request->billing_address,
            'billing_address_line_2' => $request->billing_address_line_2,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_zip'=> $request->billing_zip,
            'billing_country' => $request->billing_country,
            'vat_id' => $request->vat_id,
            'extra_billing_information' => $request->extra_billing_information,
        ]);
        if ($updated) {
            return response()->json(['success' => true, 'data' => $updated, 'status' => 200], Response::HTTP_OK);
        } else {
            return response()->json(['success' => false, 'data' => 'Card Info Not Updated', 'status' => 401], Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
