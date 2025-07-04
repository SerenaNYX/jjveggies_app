<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

   // protected $table = 'carts';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function totalCost() // IS THIS NEEDED?
    {
        return $this->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }
}
