<?php

namespace App\Models;

use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'img',
        'description', 'stock', 'category_id',
    ];

    public function orders()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $appends = ['img_path'];

    public function getImgPathAttribute()
    {
        return asset("uploads/products/" . $this->attributes['img']);
    }


}
