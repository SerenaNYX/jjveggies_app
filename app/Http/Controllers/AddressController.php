<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        return view('addresses', compact('user'));
    }

    // Store a new address
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|size:5',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        ]);

        $address = new Address([
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
        ]);

        $request->user()->addresses()->save($address);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'address' => $address]);
        }

        return redirect()->route('address.index')->with('success', 'Address added successfully.');
    }

    // Update an existing address
    public function update(Request $request, Address $address)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|size:5',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        ]);

        $address->update([
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
        ]);

    return redirect()->route('address.index')->with('success', 'Address updated successfully.');
}

    // Delete an address
    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->route('address.index')->with('success', 'Address deleted successfully.');
    }
}