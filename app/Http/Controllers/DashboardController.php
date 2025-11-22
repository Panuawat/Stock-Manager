<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. สรุปยอดรวม
        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        
        // 2. สินค้าที่ต้องเติมด่วน (Low Stock)
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 3. รายการเคลื่อนไหวล่าสุด 5 รายการ
        $recentTransactions = Transaction::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
