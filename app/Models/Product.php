<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'SKU',
        'name',
        'image',
        'brand_id',
        'category_id'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return brand_name
     */
    public function getBrandNameAttribute()
    {
        return $this->brand->name;
    }

    /**
     * @return category_name
     */
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }
}
