<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

 //   protected $table = 'cart_items';
    protected $fillable = ['cart_id', 'product_id', 'option_id', 'quantity'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function getSubtotalAttribute() // IS THIS NEEDED?
    {
        return $this->option->price * $this->quantity;
    }
}