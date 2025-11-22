<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ทำรายการ รับเข้า / เบิกออก') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- แสดง Error รวม (ถ้ามี) --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        {{-- 1. เลือกสินค้า --}}
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">สินค้า</label>
                            <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- กรุณาเลือกสินค้า --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (คงเหลือ: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. ประเภทรายการ (รับเข้า / เบิกออก) --}}
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">ประเภทรายการ</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>รับเข้า (Stock In)</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>เบิกออก (Stock Out)</option>
                            </select>
                        </div>

                        {{-- 3. จำนวน --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">จำนวน</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>

                        {{-- 4. หมายเหตุ --}}
                        <div class="mb-4">
                            <label for="note" class="block text-sm font-medium text-gray-700">หมายเหตุ (Optional)</label>
                            <textarea name="note" id="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('note') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">ยกเลิก</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                บันทึกรายการ
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
