<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('เพิ่มสินค้าใหม่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- ฟอร์มส่งข้อมูลไปที่ Route 'products.store' --}}
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 1. ชื่อสินค้า --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อสินค้า</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        {{-- 2. หมวดหมู่ (Dropdown) --}}
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">หมวดหมู่</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- 3. ราคา --}}
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">ราคาขาย (บาท)</label>
                                <input type="number" name="price" id="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- 4. จำนวนตั้งต้น --}}
                            <div class="mb-4">
                                <label for="stock" class="block text-sm font-medium text-gray-700">จำนวนสินค้า (เริ่มต้น)</label>
                                <input type="number" name="stock" id="stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- 5. จุดแจ้งเตือน --}}
                            <div class="mb-4">
                                <label for="min_stock" class="block text-sm font-medium text-gray-700">แจ้งเตือนเมื่อต่ำกว่า</label>
                                <input type="number" name="min_stock" id="min_stock" value="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>

                        {{-- 6. รายละเอียด --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียดสินค้า</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>

                        {{-- 7. รูปภาพ --}}
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">รูปภาพสินค้า</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                บันทึกสินค้า
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>