<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'status',
        'image',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'status',
        'image',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the full URL for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? url($this->image) : null;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
