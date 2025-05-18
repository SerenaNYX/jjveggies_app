<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_id',
        'original_name',
        'path',
        'mime_type',
        'size'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}