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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // เชื่อมกับ User (คนทำรายการ)
            $table->foreignId('user_id')->constrained();
            
            // เชื่อมกับ Product (สินค้าที่ถูกทำรายการ)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['in', 'out']); // ประเภท: 'in' (รับเข้า), 'out' (เบิกออก)
            $table->integer('amount');           // จำนวนที่เปลี่ยนแปลง
            $table->string('note')->nullable();  // หมายเหตุ (เช่น รับของจาก supplier A)
            
            $table->timestamps(); // วันเวลาที่ทำรายการ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
