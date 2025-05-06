<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Referral and points
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->referralCode()->create([
                'code' => ReferralCode::generateUniqueCode()
            ]);
        });

        // For existing users without codes
        static::retrieved(function ($user) {
            if (!$user->referralCode) {
                $user->referralCode()->create([
                    'code' => ReferralCode::generateUniqueCode()
                ]);
            }
        });
    }
    
    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function pointsHistory()
    {
        return $this->hasMany(UserPointsHistory::class);
    }

    public function addPoints($points, $source, $reference = null)
    {
        $this->points += $points;
        $this->save();

        $this->pointsHistory()->create([
            'points' => $points,
            'source' => $source,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
        ]);

        return $this;
    }
}