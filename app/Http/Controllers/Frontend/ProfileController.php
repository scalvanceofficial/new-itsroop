<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('Frontend.Pages.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.'
        ], 200);
    }

    public function addresses()
    {
        $user = Auth::user();

        $addresses = Address::where('user_id', $user->id)->get();

        return view('Frontend.Pages.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'recipient_first_name' => 'required',
            'recipient_last_name' => 'required',
            'type' => 'required',
            'address_line_1' => 'required',
            'city' => 'required|string|max:255',
            'pincode' => 'required|numeric|digits:6',
        ], [
            'recipient_first_name.required' => 'First name is required.',
            'recipient_last_name.required' => 'Last name is required.',
            'pincode.required' => 'Pincode is required.',
            'pincode.numeric' => 'Pincode must be a number.',
        ]);

        $user = Auth::user();

        if ($request->default == 'YES') {
            $address = Address::where('user_id', $user->id)->first();

            if ($address) {
                $address->update([
                    'default' => 'NO'
                ]);
            }
        }

        Address::create([
            'user_id' => $user->id,
            'recipient_first_name' => $request->recipient_first_name,
            'recipient_last_name' => $request->recipient_last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'type' => $request->type,
            'default' => $request->default ? 'YES' : 'NO',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address added successfully.'
        ], 200);
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'recipient_first_name' => 'required',
            'recipient_last_name' => 'required',
            'address_line_1' => 'required',
            'city' => 'required|string|max:255',
            'pincode' => 'required|numeric|digits:6',
        ], [
            'recipient_first_name.required' => 'First name is required.',
            'recipient_last_name.required' => 'Last name is required.',
            'pincode.required' => 'Pincode is required.',
            'pincode.numeric' => 'Pincode must be a number.',
        ]);


        $user = Auth::user();

        if ($request->default == 'YES') {
            $default_address = Address::where('user_id', $user->id)->where('default', 'YES')->first();

            if ($default_address) {
                $default_address->update([
                    'default' => 'NO'
                ]);
            }
        }

        $address = Address::find($request->address_id);

        $address->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully.'
        ], 200);
    }

    public function deleteAddress(Request $request)
    {
        $address = Address::find($request->address_id);

        if ($address->default == 'YES') {
            return response()->json([
                'status' => 'error',
                'message' => 'Default address can not be deleted.'
            ], 400);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully.'
        ], 200);
    }
}
