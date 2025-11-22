<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('แก้ไขสินค้า: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- ส่งข้อมูลไปที่ Route 'products.update' --}}
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- สำคัญมาก! บอก Laravel ว่านี่คือการแก้ไข --}}

                        {{-- 1. ชื่อสินค้า --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อสินค้า</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $product->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        {{-- 2. หมวดหมู่ (Dropdown + Selected) --}}
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">หมวดหมู่</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- 3. ราคา --}}
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">ราคาขาย (บาท)</label>
                                <input type="number" name="price" id="price" step="0.01" 
                                       value="{{ old('price', $product->price) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- 4. จำนวนคงเหลือ (แก้ไขได้โดยตรงในหน้านี้) --}}
                            <div class="mb-4">
                                <label for="stock" class="block text-sm font-medium text-gray-700">จำนวนสินค้าคงเหลือ</label>
                                <input type="number" name="stock" id="stock" 
                                       value="{{ old('stock', $product->stock) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            {{-- 5. จุดแจ้งเตือน --}}
                            <div class="mb-4">
                                <label for="min_stock" class="block text-sm font-medium text-gray-700">แจ้งเตือนเมื่อต่ำกว่า</label>
                                <input type="number" name="min_stock" id="min_stock" 
                                       value="{{ old('min_stock', $product->min_stock) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>

                        {{-- 6. รายละเอียด --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียดสินค้า</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product->description) }}</textarea>
                        </div>

                        {{-- 7. รูปภาพ (แสดงรูปเก่า + อัปโหลดใหม่) --}}
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">รูปภาพสินค้า</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            
                            @if ($product->image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-1">รูปภาพปัจจุบัน:</p>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" class="h-32 w-auto object-cover rounded-md border">
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ยกเลิก
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>