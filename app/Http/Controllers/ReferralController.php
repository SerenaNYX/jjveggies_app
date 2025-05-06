<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use App\Models\User;
use App\Models\UserPointsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function showEnterReferral()
    {
        $user = Auth::user();
        
        if ($user->has_entered_referral) {
            return redirect()->back()->with('info', 'You have already entered a referral code.');
        }
        
        return view('referral.enter');
    }

    public function processReferral(Request $request)
    {
        $user = Auth::user();
        
        if ($user->has_entered_referral) {
            return redirect()->back()->with('info', 'You have already entered a referral code.');
        }

        $request->validate(['code' => 'required|string|exists:referral_codes,code']);
        
        $referralCode = ReferralCode::where('code', $request->code)->first();
        
        if ($referralCode->user_id == $user->id) {
            return back()->with('error', 'You cannot use your own referral code.');
        }
        
        // Award points to both users
        $referrer = User::find($referralCode->user_id);
        $referrer->addPoints(150, 'referral', $user);
        
        $user->addPoints(150, 'referral', $referrer);
        $user->referred_by = $referralCode->user_id;
        $user->has_entered_referral = true;
        $user->save();
        
        return redirect()->back()->with('success', 'Referral code applied successfully! You earned 150 points.');
    }

}