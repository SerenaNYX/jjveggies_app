<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'user_id', 'code', 'points_required', 
        'discount_amount', 'minimum_spend', 'is_used', 'used_at'
    ];

    protected $casts = [
        'discount_amount' => 'float',
        'minimum_spend' => 'float',
        'points_required' => 'integer',
        'is_used' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueCode()
    {
        do {
            $code = 'VOUCHER-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}