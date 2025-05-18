<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginemail' => 'required',
            'loginpassword' => 'required'
        ]);

        $user = User::where('email', $incomingFields['loginemail'])->first();

        if ($user && $user->banned_at) {
            return back()->withErrors(['loginemail' => 'Your account is banned.']);
        }

        if (Auth::attempt(['email' => $incomingFields['loginemail'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'loginemail' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:25', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
            'contact' => ['required', 'regex:/^\+?[0-9]{10,15}$/'], // OR use {10,12}
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);

        Auth::login($user);
        return redirect('/');   
        
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // update in user's profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|min:3|max:25',
            'contact' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->contact = $request->contact;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // DON'T REMOVE!

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function denyCustomer(User $user)
    {
        $user->banned_at = now();
        $user->save();
        return redirect()->route('admin.employees.index')->with('success', 'Customer access denied successfully.');
    }

    public function unbanCustomer(User $user)
    {
        $user->banned_at = null;
        $user->save();
        return redirect()->route('admin.employees.index')->with('success', 'Customer access restored successfully.');
    }

}
