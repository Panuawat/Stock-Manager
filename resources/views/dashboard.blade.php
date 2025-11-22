<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard ภาพรวมร้านค้า') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Card 1: สินค้าทั้งหมด -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">สินค้าทั้งหมด</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }} รายการ</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: สินค้าต้องเติม (Low Stock) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">สินค้าใกล้หมด</p>
                            <p class="text-2xl font-bold text-red-600">{{ $lowStockCount }} รายการ</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: ทางลัด (Actions) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">จัดการสต็อก</p>
                        <p class="text-lg font-bold text-gray-800">ทำรายการด่วน</p>
                    </div>
                    <a href="{{ route('transactions.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded shadow">
                        + รับเข้า/เบิกออก
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- 2. ตารางสินค้าใกล้หมด (Low Stock Alert) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-red-50">
                        <h3 class="text-lg font-semibold text-red-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            สินค้าต้องเติมสต็อก (5 อันดับแรก)
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($lowStockProducts->isEmpty())
                            <p class="text-gray-500 text-center py-4">เยี่ยมมาก! ไม่มีสินค้าใกล้หมด</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($lowStockProducts as $product)
                                    <li class="py-3 flex justify-between items-center">
                                        <div class="flex items-center">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="h-10 w-10 rounded-full object-cover mr-3" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500 mr-3">No Pic</div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">ขั้นต่ำ: {{ $product->min_stock }}</p>
                                            </div>
                                        </div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            เหลือ {{ $product->stock }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-4 text-center">
                                <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:text-blue-900">ดูทั้งหมด &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 3. รายการเคลื่อนไหวล่าสุด (Recent Transactions) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800">รายการเคลื่อนไหวล่าสุด</h3>
                    </div>
                    <div class="p-6">
                        @if($recentTransactions->isEmpty())
                            <p class="text-gray-500 text-center py-4">ยังไม่มีรายการเคลื่อนไหว</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $transaction->product->name ?? 'สินค้าถูกลบ' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                โดย {{ $transaction->user->name ?? 'N/A' }} • {{ $transaction->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($transaction->type == 'in')
                                                <span class="text-green-600 font-bold text-sm">+{{ $transaction->amount }}</span>
                                                <p class="text-xs text-gray-400">รับเข้า</p>
                                            @else
                                                <span class="text-red-600 font-bold text-sm">-{{ $transaction->amount }}</span>
                                                <p class="text-xs text-gray-400">เบิกออก</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-4 text-center">
                                <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:text-blue-900">ดูประวัติทั้งหมด &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
