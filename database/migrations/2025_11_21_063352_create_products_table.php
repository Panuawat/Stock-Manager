<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // เชื่อมกับหมวดหมู่ (ถ้าหมวดหมู่ถูกลบ ให้สินค้าเป็น null หรือลบตามก็ได้ ในที่นี้ขอให้ลบตาม)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->string('name');             // ชื่อสินค้า
            $table->text('description')->nullable(); // รายละเอียด
            $table->decimal('price', 10, 2);    // ราคาขาย (ทศนิยม 2 ตำแหน่ง)
            $table->integer('stock')->default(0); // จำนวนคงเหลือปัจจุบัน
            $table->integer('min_stock')->default(10); // แจ้งเตือนเมื่อต่ำกว่านี้
            $table->string('image')->nullable(); // รูปภาพสินค้า
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
