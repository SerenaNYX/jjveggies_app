<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_method',
        'payment_status',
        'status'
    ];

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
}