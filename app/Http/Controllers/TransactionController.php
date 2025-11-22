<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import DB เพื่อใช้ Transaction Database

class TransactionController extends Controller
{
    /**
     * 1. แสดงประวัติการทำรายการทั้งหมด
     */
    public function index()
    {
        // ดึงข้อมูล Transaction ทั้งหมด, พร้อมข้อมูล User และ Product
        // เรียงจากล่าสุดไปเก่าสุด
        $transactions = Transaction::with(['user', 'product'])->latest()->paginate(20);

        return view('transactions.index', ['transactions' => $transactions]);
    }

    /**
     * 2. แสดงฟอร์ม "ทำรายการ" (รับเข้า/เบิกออก)
     */
    public function create()
    {
        // ส่งรายชื่อสินค้าไปให้เลือกใน Dropdown
        $products = Product::all();
        return view('transactions.create', ['products' => $products]);
    }

    /**
     * 3. บันทึกรายการ และ อัปเดตสต็อก (หัวใจสำคัญ!)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out', // ต้องเป็น 'in' หรือ 'out' เท่านั้น
            'amount' => 'required|integer|min:1', // ต้องเป็นจำนวนเต็ม และมากกว่า 0
            'note' => 'nullable|string|max:255',
        ]);

        // ใช้ DB::transaction เพื่อรับประกันว่า...
        // ถ้า "ตัดสต็อก" พลาด -> จะไม่ "บันทึกประวัติ"
        // ถ้า "บันทึกประวัติ" พลาด -> จะไม่ "ตัดสต็อก"
        // (ต้องสำเร็จทั้งคู่ หรือ ล้มเหลวทั้งคู่)
        try {
            DB::transaction(function () use ($request) {
                
                // 1. ดึงข้อมูลสินค้า
                $product = Product::findOrFail($request->product_id);

                // 2. คำนวณสต็อก
                if ($request->type === 'in') {
                    // ถ้ารับเข้า: บวกเพิ่ม
                    $product->increment('stock', $request->amount);
                } else {
                    // ถ้าเบิกออก: ตรวจสอบก่อนว่ามีของพอไหม?
                    if ($product->stock < $request->amount) {
                        throw new \Exception("สินค้าไม่พอจำหน่าย (คงเหลือ: {$product->stock})");
                    }
                    // ตัดยอด
                    $product->decrement('stock', $request->amount);
                }

                // 3. บันทึกประวัติลงตาราง transactions
                Transaction::create([
                    'user_id' => Auth::id(), // คนทำรายการ
                    'product_id' => $request->product_id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'note' => $request->note,
                ]);
            });

            return redirect()->route('products.index')->with('success', 'บันทึกรายการปรับปรุงสต็อกเรียบร้อยแล้ว!');

        } catch (\Exception $e) {
            // ถ้ามี Error (เช่น ของไม่พอ) ให้เด้งกลับไปหน้าเดิม พร้อมข้อความแจ้งเตือน
            return back()->withInput()->withErrors(['msg' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
}