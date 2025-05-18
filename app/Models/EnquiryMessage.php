<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'sender_id',
        'sender_type',
        'message'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }
}