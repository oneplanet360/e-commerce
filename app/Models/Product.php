<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductGallery;
use App\Models\Review;
use App\Models\ProductVariant;

class Product extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'brand',
        'color',
        'size',
        'image',
        'short_description',
        'description',
        'specifications',
        'features',
        'price',
        'discount_price',
        'cost_usd',
        'currency',
        'tax_rate',
        'tax_id',
        'width',
        'height',
        'weight_grams',
        'shipping_fees',
        'stock',
        'status',
        'is_enabled',
        'is_template',
        'date_added',
        'is_popular',
        'delivery_type',
        'delivery_charge',
        'tags',
        'meta_title',
        'meta_description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'status',
        'image',
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'specifications' => 'array',
        'features' => 'array',
        'status' => 'boolean',
        'is_enabled' => 'boolean',
        'is_template' => 'boolean',
        'is_popular' => 'boolean',
        'date_added' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brandRef()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the gallery images for the product.
     */
    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the full URL for the product image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? url($this->image) : null;
    }

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }
        });
    }
}
