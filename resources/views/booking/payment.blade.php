<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Xác Nhận Đặt Vé') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-2xl border-t-4 border-red-600">
                <div class="p-8">
                    
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8 pb-4 border-b border-gray-200">
                        Thông Tin Đặt Vé
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        
                        <!-- Movie Info (Left Column) -->
                        <div class="md:col-span-4 space-y-6">
                            @if($showtime->movie->poster)
                                <img src="{{ Storage::url($showtime->movie->poster) }}" 
                                     class="w-full h-auto object-cover rounded-xl shadow-lg border-2 border-white transform hover:scale-105 transition-transform duration-300"
                                     alt="{{ $showtime->movie->title }}">
                            @endif
                            
                            <div>
                                <h4 class="text-2xl font-black text-red-600 dark:text-red-500 mb-2 uppercase leading-tight">
                                    {{ $showtime->movie->title }}
                                </h4>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-4">
                                    {{ $showtime->movie->duration }} phút
                                </div>
                                
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border border-gray-100 dark:border-gray-600 text-sm space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Rạp</span>
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $showtime->room->cinema->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Phòng</span>
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $showtime->room->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Xuất chiếu</span>
                                        <span class="font-bold text-red-600">{{ $showtime->start_time->format('H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Ngày</span>
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $showtime->start_time->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details & Payment (Right Column) -->
                        <div class="md:col-span-8">
                            <form method="POST" action="{{ route('booking.checkout', $showtime) }}">
                                @csrf
                                <input type="hidden" name="seat_ids" value="{{ $seatIds }}">

                                <!-- Selected Seats -->
                                <div class="mb-8 p-6 bg-gradient-to-br from-red-50 to-white rounded-xl border border-red-100 shadow-sm">
                                    <h4 class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-3">Ghế đã chọn</h4>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($seats as $seat)
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-red-600 text-white rounded-lg font-bold shadow-md text-sm">
                                                {{ $seat->row }}{{ $seat->number }}
                                            </span>
                                        @endforeach
                                    </div>
                                    
                                    <div class="flex justify-between items-end pt-4 border-t border-red-100">
                                        <span class="text-gray-600 font-medium">Tổng cộng</span>
                                        <span class="text-3xl font-black text-red-600">
                                            {{ number_format(count($seats) * 100000, 0, ',', '.') }} đ
                                        </span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-10">
                                    <h4 class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-3">Phương thức thanh toán</h4>
                                    
                                    <div class="flex flex-col gap-3">
                                        
                                        <!-- Cash -->
                                        <label class="relative flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all group">
                                            <div class="flex items-center h-5">
                                                <input type="radio" name="payment_method" value="cash" checked class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                                            </div>
                                            <div class="ml-4 flex items-center flex-1">
                                                <span class="text-2xl mr-3">💵</span>
                                                <div class="flex flex-col">
                                                    <span class="block text-sm font-bold text-gray-900 group-hover:text-red-700">Tiền Mặt</span>
                                                    <span class="block text-xs text-gray-500">Thanh toán tại quầy</span>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- VNPay -->
                                        <label class="relative flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all group">
                                            <div class="flex items-center h-5">
                                                <input type="radio" name="payment_method" value="vnpay" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            </div>
                                            <div class="ml-4 flex items-center flex-1">
                                                <span class="text-2xl mr-3 font-bold text-blue-600">VNP</span>
                                                <div class="flex flex-col">
                                                    <span class="block text-sm font-bold text-gray-900 group-hover:text-blue-700">VNPay</span>
                                                    <span class="block text-xs text-gray-500">Quét mã QR</span>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- MoMo -->
                                        <label class="relative flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pink-400 hover:bg-pink-50 transition-all group">
                                            <div class="flex items-center h-5">
                                                <input type="radio" name="payment_method" value="momo" class="w-5 h-5 text-pink-600 border-gray-300 focus:ring-pink-500">
                                            </div>
                                            <div class="ml-4 flex items-center flex-1">
                                                <span class="text-2xl mr-3 font-bold text-pink-600">Mo</span>
                                                <div class="flex flex-col">
                                                    <span class="block text-sm font-bold text-gray-900 group-hover:text-pink-700">MoMo</span>
                                                    <span class="block text-xs text-gray-500">Ví điện tử</span>
                                                </div>
                                            </div>
                                        </label>

                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex gap-4 pt-6 border-t border-gray-100">
                                    <a href="{{ route('booking.show', $showtime) }}" 
                                       class="px-6 py-4 rounded-xl font-bold text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors border border-transparent hover:border-red-200">
                                        ← Quay lại
                                    </a>
                                    
                                    <button type="submit" 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg shadow-red-500/30 transform hover:-translate-y-1 transition-all duration-200 text-lg uppercase tracking-wide">
                                        Xác Nhận Thanh Toán
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
