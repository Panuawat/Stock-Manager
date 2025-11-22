<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'type',   // 'in' หรือ 'out'
        'amount', // จำนวน
        'note',
    ];

    // ความสัมพันธ์: Transaction นี้ "เป็นของ" (belongsTo) User คนไหน
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์: Transaction นี้ "เป็นของ" (belongsTo) สินค้าตัวไหน
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}