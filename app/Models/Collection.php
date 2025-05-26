<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'category',
        'is_featured',
        'is_active',
        'is_new'
    ];
    
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_new' => 'boolean',
    ];
    
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = Str::slug($collection->name);
            }
        });
    }
    
    /**
     * Get the products belonging to this collection
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_collections');
    }
    
    /**
     * Get the URL for the collection's image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        return null;
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}