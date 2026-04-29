<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $fillable = ['product_id', 'image_path'];

    protected $hidden = [
        'product_id',
        'image_path',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for the gallery image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? url($this->image_path) : null;
    }
}
