<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Xác Nhận Đặt Vé') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-2xl border-t-4 border-red-600">
                <div class="p-8">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 border-b pb-4">Thông Tin Đặt Vé</h3>

                    <!-- Movie & Showtime Info -->
                    <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-xl shadow-inner">
                        <div class="flex gap-8 flex-col md:flex-row">
                            @if($showtime->movie->poster)
                                <img src="{{ Storage::url($showtime->movie->poster) }}" 
                                     class="w-40 h-60 object-cover rounded-lg shadow-md mx-auto md:mx-0"
                                     alt="{{ $showtime->movie->title }}">
                            @endif
                            <div class="flex-1">
                                <h4 class="text-3xl font-bold text-red-600 dark:text-red-400 mb-4 uppercase">
                                    {{ $showtime->movie->title }}
                                </h4>
                                <div class="space-y-3 text-lg text-gray-700 dark:text-gray-300">
                                    <p><strong class="w-32 inline-block text-gray-500">Rạp:</strong> {{ $showtime->room->cinema->name }}</p>
                                    <p><strong class="w-32 inline-block text-gray-500">Phòng:</strong> {{ $showtime->room->name }}</p>
                                    <p><strong class="w-32 inline-block text-gray-500">Suất chiếu:</strong> {{ $showtime->start_time->format('H:i') }} - <span class="text-indigo-600 font-bold">{{ $showtime->start_time->format('d/m/Y') }}</span></p>
                                    <p><strong class="w-32 inline-block text-gray-500">Thời lượng:</strong> {{ $showtime->movie->duration }} phút</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Seats -->
                    <div class="mb-8">
                        <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-4">Ghế Đã Chọn</h4>
                        <div class="flex flex-wrap gap-3">
                            @foreach($seats as $seat)
                                <span class="px-5 py-3 bg-red-100 text-red-800 border border-red-200 rounded-xl font-bold font-mono text-lg shadow-sm">
                                    {{ $seat->row }}{{ $seat->number }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="mb-8 p-6 bg-yellow-50 dark:bg-gray-700 rounded-xl border border-yellow-200 dark:border-gray-600">
                        <h4 class="font-bold text-xl text-yellow-800 dark:text-yellow-400 mb-4">Chi Tiết Giá</h4>
                        @php
                            $pricePerSeat = 100000;
                            $totalPrice = count($seats) * $pricePerSeat;
                        @endphp
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300 text-lg">
                                <span>Giá vé ({{ count($seats) }} x {{ number_format($pricePerSeat, 0, ',', '.') }} VND)</span>
                                <span>{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                            </div>
                            <div class="flex justify-between text-2xl font-bold text-gray-900 dark:text-gray-100 pt-4 border-t border-yellow-200 dark:border-gray-600 mt-2">
                                <span>Tổng Cộng</span>
                                <span class="text-red-600">{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <form method="POST" action="{{ route('booking.checkout', $showtime) }}">
                        @csrf
                        <input type="hidden" name="seat_ids" value="{{ $seatIds }}">

                        <div class="mb-8">
                            <h4 class="font-bold text-xl text-gray-900 dark:text-gray-100 mb-4">Phương Thức Thanh Toán</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" checked class="peer sr-only">
                                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4 text-3xl"></div>
                                    <div class="font-bold text-lg">Tiền Mặt</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Thanh toán tại quầy</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 rounded-xl pointer-events-none"></div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block text-red-500 bg-white rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                                
                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="vnpay" class="peer sr-only">
                                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">VNP</div>
                                    <div class="font-bold text-lg">VNPay</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Quét mã QR</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 rounded-xl pointer-events-none"></div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block text-red-500 bg-white rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>

                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="momo" class="peer sr-only">
                                    <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold">MoMo</div>
                                    <div class="font-bold text-lg">MoMo</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Ví điện tử</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 rounded-xl pointer-events-none"></div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block text-red-500 bg-white rounded-full">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-6 mt-10">
                            <a href="{{ route('booking.show', $showtime) }}" 
                               class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-4 px-6 rounded-xl transition text-lg flex items-center justify-center gap-2 group">
                                <span class="group-hover:-translate-x-1 transition-transform">←</span> Quay Lại
                            </a>
                            <button type="submit" 
                                    class="flex-2 w-2/3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg hover:shadow-xl hover:scale-105 transform duration-200 text-lg uppercase tracking-wider">
                                Xác Nhận Thanh Toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
