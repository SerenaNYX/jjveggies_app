<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_number',
        'name', 
        'slug', 
        'image', 
        'category_id', 
        'description',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = self::generateUniqueSlug($product->name);
            $product->product_number = self::generateUniqueProductNumber();
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = self::generateUniqueSlug($product->name, $product->id);
            }
        });
    }
    protected static function generateUniqueProductNumber()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $productNumber = '';
        
        do {
            $productNumber = '';
            for ($i = 0; $i < 4; $i++) {
                $productNumber .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (static::where('product_number', $productNumber)->exists());
        
        return $productNumber;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Relationship with the ProductOption model
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    // Relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope to filter products by category
    public function scopeCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    // Accessor to get the image with a default fallback
    public function getImageAttribute($value)
    {
        return $value ?? 'img/vegetables/vegetables.png';
    }

    
    protected static function generateUniqueSlug($name, $productId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $productId)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
