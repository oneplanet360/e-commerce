<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingBanner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'background_color',
        'image',
        'link',
        'position',
        'is_active',
        'sort_order',
    ];

    protected $hidden = ['image'];

    protected $appends = ['image_url'];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the full URL for the banner image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? url($this->image) : null;
    }
}
