<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name', 'description', 'price',
        'category', 'image_url', 'available',
        'has_discount', 'discount_pct'
    ];

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews() {

        return $this->hasMany(Review::class);
    }
}