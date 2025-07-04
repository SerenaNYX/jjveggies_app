<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_number',
        'user_id',
        'staff_id',
        'name',
        'contact_number',
        'email',
        'message',
        'status',
        'response'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function generateEnquiryNumber()
    {
        $prefix = 'ENQ-' . date('Ymd') . '-';
        $latest = static::where('enquiry_number', 'like', $prefix . '%')->latest()->first();

        if ($latest) {
            $number = (int) str_replace($prefix, '', $latest->enquiry_number) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Employee::class, 'staff_id');
    }

    public function attachments()
    {
        return $this->hasMany(EnquiryAttachment::class);
    }
    /**
     * Scope a query to only include pending enquiries.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress enquiries.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include resolved enquiries.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Check if the enquiry is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the enquiry is in progress.
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the enquiry is resolved.
     */
    public function isResolved()
    {
        return $this->status === 'resolved';
    }
}