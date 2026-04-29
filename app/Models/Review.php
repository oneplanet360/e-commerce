<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'customer_name',
        'rating',
        'date_added',
        'status',
        'review_text',
    ];

    protected $casts = [
        'date_added' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
