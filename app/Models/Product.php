<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'min_stock',
        'image',
    ];

    // ความสัมพันธ์: 1 สินค้า "เป็นของ" (belongsTo) 1 หมวดหมู่
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ความสัมพันธ์: 1 สินค้า "มี" (hasMany) หลายรายการเคลื่อนไหว (Transactions)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}