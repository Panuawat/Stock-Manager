<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างหมวดหมู่ตัวอย่าง 5 รายการ
        $categories = [
            'เครื่องแต่งกาย',
            'อุปกรณ์อิเล็กทรอนิกส์',
            'อาหารและเครื่องดื่ม',
            'ของใช้ในบ้าน',
            'อุปกรณ์กีฬา'
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}