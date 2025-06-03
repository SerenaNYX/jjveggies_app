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

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'postal_code' => [
                'required',
                'digits:5',
                function ($attribute, $value, $fail) {
                    $validPostcodes = ['81750', '81100', '81300', '79100'];
                    
                    // Check specific postcodes
                    if (in_array($value, $validPostcodes)) {
                        return;
                    }
                    
                    // Check Johor Bahru range (80000-81300)
                    $numericPostcode = (int)$value;
                    if ($numericPostcode >= 80000 && $numericPostcode <= 81300) {
                        return;
                    }
                    
                    $fail('We only deliver to Permas Jaya, Johor Bahru, Austin Heights, Skudai, and Iskandar Puteri.');
                }
            ],
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
    // In your AddressController.php

    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'postal_code' => [
                'required',
                'digits:5',
                function ($attribute, $value, $fail) {
                    $validPostcodes = ['81750', '81100', '81300', '79100'];
                    
                    // Check specific postcodes
                    if (in_array($value, $validPostcodes)) {
                        return;
                    }
                    
                    // Check Johor Bahru range (80000-81300)
                    $numericPostcode = (int)$value;
                    if ($numericPostcode >= 80000 && $numericPostcode <= 81300) {
                        return;
                    }
                    
                    $fail('We only deliver to Permas Jaya, Johor Bahru, Austin Heights, Skudai, and Iskandar Puteri.');
                }
            ],
            'phone' => 'required|string|max:12'
        ]);
        
        $address->update($validated);
        
        return redirect()->route('address.index')->with('success', 'Address updated successfully');
    }

    // Delete an address
    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->route('address.index')->with('success', 'Address deleted successfully.');
    }
}