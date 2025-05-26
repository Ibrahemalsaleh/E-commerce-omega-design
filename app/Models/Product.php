<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock_quantity',
        'image_path',
        'is_featured',
        'is_new_arrival',
        'is_bestseller',
        'category', // Added for product filtering (wallpaper, parquet, etc.)
        'height',   // For dimensions shown in product cards
        'width',    // For dimensions shown in product cards
        'thickness', // For dimensions shown in product cards
        'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    /**
     * Get the collections for this product
     */
    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'product_collections');
    }

    /**
     * Get the cart items for this product
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the URL for the product's image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        
        return asset('https://picsum.photos/200/300');
    }
    
    /**
     * Get formatted dimensions of the product
     */
    public function getDimensionsAttribute()
    {
        $dimensions = [];
        
        if ($this->height) {
            $dimensions[] = "H: {$this->height}";
        }
        
        if ($this->width) {
            $dimensions[] = "W: {$this->width}";
        }
        
        if ($this->thickness) {
            $dimensions[] = "T: {$this->thickness}";
        }
        
        return !empty($dimensions) ? implode(', ', $dimensions) : null;
    }
}