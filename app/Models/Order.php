<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'voucher_code',  
        'discount_amount',
        'driver_id',
        'cancellation_reason',
    ];

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = static::generateOrderNumber();
        });
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $latest = static::where('order_number', 'like', $prefix . '%')->latest()->first();

        if ($latest) {
            $number = (int) str_replace($prefix, '', $latest->order_number) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }
    
    public function updateStatus($newStatus, $notes = null)
    {
        $this->status = $newStatus;
        $this->save();

        $this->statusHistory()->create([
            'status' => $newStatus,
            'notes' => $notes
        ]);

        // You can add notification logic here if needed
    }

    // Add this method to handle cancellation
public function cancel($reason = null, $changedBy = null)
{
    $this->status = 'cancelled';
    $this->cancellation_reason = $reason;
    $this->save();

    $this->statusHistory()->create([
        'status' => 'cancelled',
        'notes' => $reason ?? 'Order cancelled',
        'changed_by' => $changedBy ?? auth('employee')->user()->name
    ]);
}

// Add this method to handle refund
public function refund($notes = null, $changedBy = null)
{
    $this->status = 'refunded';
    $this->save();

    $this->statusHistory()->create([
        'status' => 'refunded',
        'notes' => $notes ?? 'Order refunded' . ($this->cancellation_reason ? " (Cancellation reason: {$this->cancellation_reason})" : ''),
        'changed_by' => $changedBy ?? auth('employee')->user()->name
    ]);
}
}