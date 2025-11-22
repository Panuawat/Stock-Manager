<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    /**
     * 1. รายงานสินค้าคงเหลือ (CSV)
     */
    public function stock()
    {
        $products = Product::with('category')->orderBy('name')->get();

        $filename = 'stock-report-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Thai character display in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'ID',
                'ชื่อสินค้า',
                'หมวดหมู่',
                'รายละเอียด',
                'ราคา (บาท)',
                'คงเหลือ',
                'จุดสั่งซื้อ (Min Stock)',
                'สถานะ',
                'วันที่สร้าง',
                'วันที่อัปเดตล่าสุด',
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name ?? '-',
                    $product->description,
                    number_format($product->price, 2),
                    $product->stock,
                    $product->min_stock,
                    $product->stock <= $product->min_stock ? 'สินค้าใกล้หมด' : 'ปกติ',
                    $product->created_at->format('d/m/Y H:i'),
                    $product->updated_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 2. รายงานประวัติการเคลื่อนไหว (CSV)
     */
    public function transactions()
    {
        $transactions = Transaction::with(['user', 'product'])
            ->latest()
            ->take(1000)
            ->get();

        $filename = 'transactions-report-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Thai character display in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'วันที่/เวลา',
                'ผู้ทำรายการ',
                'ชื่อสินค้า',
                'ประเภทรายการ',
                'จำนวน',
                'หมายเหตุ',
            ]);

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('d/m/Y H:i:s'),
                    $transaction->user->name ?? 'N/A',
                    $transaction->product->name ?? '(สินค้าถูกลบ)',
                    $transaction->type === 'in' ? 'รับเข้า' : 'เบิกออก',
                    ($transaction->type === 'in' ? '+' : '-') . $transaction->amount,
                    $transaction->note,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
