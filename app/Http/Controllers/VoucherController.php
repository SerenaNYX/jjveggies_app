<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Auth::user()->vouchers()->orderBy('is_used')->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function redeem(Request $request)
    {
        $user = Auth::user();
        
        $tiers = [
            100 => ['discount' => 3, 'min_spend' => 5],
            250 => ['discount' => 8, 'min_spend' => 10],
            500 => ['discount' => 20, 'min_spend' => 22],
        ];
        
        $points = $request->input('points');
        
        if (!array_key_exists($points, $tiers)) {
            return back()->with('error', 'Invalid points redemption tier.');
        }
        
        if ($user->points < $points) {
            return back()->with('error', 'You don\'t have enough points for this voucher.');
        }
        
        $voucher = Voucher::create([
            'user_id' => $user->id,
            'code' => Voucher::generateUniqueCode(),
            'points_required' => $points,
            'discount_amount' => $tiers[$points]['discount'],
            'minimum_spend' => $tiers[$points]['min_spend'],
        ]);
        
        $user->points -= $points;
        $user->save();
        
        return back()->with('success', 'Voucher redeemed successfully! Code: ' . $voucher->code);
    }

    public function availableVouchers()
    {
        $vouchers = Auth::user()->vouchers()
            ->where('is_used', false)
            ->get();

        return response()->json($vouchers);
    }
}