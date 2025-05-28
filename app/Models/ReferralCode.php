<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReferralCode extends Model
{
    protected $fillable = ['user_id', 'code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class)->withDefault([
            'code' => 'ERROR-GENERATE-CODE'
        ]);
    }

    public function getQrCode()
    {
        return QrCode::size(200)->generate($this->code);
    }
}