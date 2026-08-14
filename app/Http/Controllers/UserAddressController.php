<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\City;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAddressController extends Controller
{
    public function getStates(Request $request)
    {
        $country = 101;
        $states = State::when(! empty($request->q), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->where('country_id', $country)->get();

        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $state = $request->state_id;
        $cities = City::whereHas('state', fn ($query) => $query->where('name', $state))->when(! empty($request->q), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))->get();

        return response()->json($cities);
    }

    public function store(UserAddressRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->merge(['phone_number' => $request->country_code.$request->phone_number]);
            UserAddress::create(array_merge($request->all(), [
                'user_id' => Auth::id(),
                'gst_number' => $request->gstnumber,
            ]));
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Address added successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'Failed to add address'], 500);
        }
    }

    public function edit($id)
    {
        $address = UserAddress::findOrFail($id);
        if (! $address) {
            abort(404);
        }

        return $address;
    }

    public function update(UserAddressRequest $request, $address)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'phone_number' => $request->country_code.$request->phone_number,
                'gst_number' => $request->gstnumber,
            ]);

            $address = UserAddress::findOrFail($address);
            $address->update($request->all());
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Address updated successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'Failed to update address'], 500);
        }

    }

    public function destroy($address)
    {
        DB::beginTransaction();

        try {
            $address = UserAddress::findOrFail($address);
            $address->delete();
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Address deleted successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'Failed to delete address'], 500);
        }
    }
}
