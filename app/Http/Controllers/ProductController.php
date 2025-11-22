<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // อย่าลืม Import Storage

// ถ้า Intelephense ยังเตือน ให้ลองเติมบรรทัดนี้ (แต่ปกติไม่ต้องใช้)
// use App\Http\Controllers\Controller; 

class ProductController extends Controller
{
    /**
     * 1. แสดงรายการสินค้าทั้งหมด
     */
    public function index()
    {
        // ดึงสินค้าทั้งหมด พร้อมข้อมูลหมวดหมู่ (เพื่อลด Query N+1)
        // เรียงจากใหม่ไปเก่า และแบ่งหน้าทีละ 10 ชิ้น
        $products = Product::with('category')->latest()->paginate(10);

        return view('products.index', ['products' => $products]);
    }

    /**
     * 2. แสดงฟอร์ม "เพิ่มสินค้า"
     */
    public function create()
    {
        // เราต้องส่ง "รายชื่อหมวดหมู่" ไปให้เลือกใน Dropdown
        $categories = Category::all();

        return view('products.create', ['categories' => $categories]);
    }

    /**
     * 3. บันทึกสินค้าใหม่
     */
    public function store(Request $request)
    {
        // ตรวจสอบความถูกต้อง (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // ต้องมี ID นี้ในตาราง categories จริง
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // รูปภาพไม่เกิน 2MB
        ]);

        // จัดการรูปภาพ (ถ้ามี)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $path;
        }

        // บันทึกลงฐานข้อมูล
        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'เพิ่มสินค้าเรียบร้อยแล้ว!');
    }

    /**
     * 4. แสดงฟอร์ม "แก้ไขสินค้า"
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * 5. บันทึกการแก้ไข
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // จัดการรูปภาพใหม่ (ถ้ามีการอัปโหลด)
        if ($request->hasFile('image')) {
            // ลบรูปเก่าออกก่อน (เพื่อไม่ให้รก Server)
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $path = $request->file('image')->store('product-images', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'อัปเดตข้อมูลสินค้าแล้ว!');
    }

    /**
     * 6. ลบสินค้า
     */
    public function destroy(Product $product)
    {
        // ลบรูปภาพด้วย (ถ้ามี)
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'ลบสินค้าเรียบร้อยแล้ว!');
    }
}