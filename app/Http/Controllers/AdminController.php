<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }
/*
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('employee')->attempt($credentials)) {
            $role = Auth::guard('employee')->user()->role;
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'staff') {
                return redirect()->route('staff.dashboard');
            } elseif ($role === 'driver') {
                return redirect()->route('driver.dashboard');
            }
        }

        return redirect()->route('admin.login')->withErrors(['email' => 'Invalid credentials.']);
    }*/

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        Log::info('Attempting login with credentials: ', $credentials);

        if (Auth::guard('employee')->attempt($credentials)) {
            $role = Auth::guard('employee')->user()->role;
            Log::info('User authenticated, role: ' . $role);

            if (Auth::guard('employee')->attempt($credentials)) {
                $role = Auth::guard('employee')->user()->role;
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($role === 'staff') {
                    return redirect()->route('staff.dashboard');
                } elseif ($role === 'driver') {
                    return redirect()->route('driver.dashboard');
                }
            }
        }

        Log::warning('Login failed for credentials: ', $credentials);
        return redirect()->route('admin.login')->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::guard('employee')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $role = Auth::guard('employee')->user()->role;

        if ($role === 'admin') {
            return view('admin.dashboard');
        } elseif ($role === 'staff') {
            return view('staff.dashboard');
        } elseif ($role === 'driver') {
            return view('driver.dashboard');
        }
    }

}
