<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // ความสัมพันธ์: 1 หมวดหมู่ "มี" (hasMany) หลายสินค้า
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}