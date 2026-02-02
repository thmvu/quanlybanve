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
                                <span class="px-5 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-bold font-mono text-lg shadow-lg transform hover:scale-105 transition-transform cursor-default">
                                    {{ $seat->row }}{{ $seat->number }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="mb-8 p-6 bg-red-50 dark:bg-gray-700 rounded-xl border border-red-100 dark:border-gray-600">
                        <h4 class="font-bold text-xl text-red-800 dark:text-red-400 mb-4">Chi Tiết Giá</h4>
                        @php
                            $pricePerSeat = 100000;
                            $totalPrice = count($seats) * $pricePerSeat;
                        @endphp
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300 text-lg">
                                <span>Giá vé ({{ count($seats) }} x {{ number_format($pricePerSeat, 0, ',', '.') }} VND)</span>
                                <span>{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                            </div>
                            <div class="flex justify-between text-2xl font-bold text-gray-900 dark:text-gray-100 pt-4 border-t border-red-200 dark:border-gray-600 mt-2">
                                <span>Tổng Cộng</span>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-pink-600">{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
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
                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-red-300 hover:shadow-md bg-white hover:bg-red-50">
                                    <input type="radio" name="payment_method" value="cash" checked class="peer sr-only">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 text-green-600 rounded-full flex items-center justify-center mb-4 text-3xl shadow-inner">💵</div>
                                    <div class="font-bold text-lg text-gray-800">Tiền Mặt</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Thanh toán tại quầy</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-50/30 rounded-xl pointer-events-none transition-all"></div>
                                    <div class="absolute top-3 right-3 hidden peer-checked:block text-white bg-red-500 rounded-full p-1 shadow-lg transform scale-100 transition-transform">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                                
                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-blue-300 hover:shadow-md bg-white hover:bg-blue-50">
                                    <input type="radio" name="payment_method" value="vnpay" class="peer sr-only">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold shadow-inner">VNP</div>
                                    <div class="font-bold text-lg text-gray-800">VNPay</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Quét mã QR</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-50/30 rounded-xl pointer-events-none transition-all"></div>
                                    <div class="absolute top-3 right-3 hidden peer-checked:block text-white bg-blue-500 rounded-full p-1 shadow-lg transform scale-100 transition-transform">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>

                                <label class="payment-card relative flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-pink-300 hover:shadow-md bg-white hover:bg-pink-50">
                                    <input type="radio" name="payment_method" value="momo" class="peer sr-only">
                                    <div class="w-16 h-16 bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 rounded-full flex items-center justify-center mb-4 text-2xl font-bold shadow-inner">MoMo</div>
                                    <div class="font-bold text-lg text-gray-800">MoMo</div>
                                    <div class="text-sm text-gray-500 text-center mt-1">Ví điện tử</div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-pink-500 peer-checked:bg-pink-50/30 rounded-xl pointer-events-none transition-all"></div>
                                    <div class="absolute top-3 right-3 hidden peer-checked:block text-white bg-pink-500 rounded-full p-1 shadow-lg transform scale-100 transition-transform">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-6 mt-10">
                            <a href="{{ route('booking.show', $showtime) }}" 
                               class="flex-1 text-center border-2 border-gray-300 hover:border-red-500 text-gray-600 hover:text-red-500 font-bold py-4 px-6 rounded-xl transition text-lg flex items-center justify-center gap-2 group bg-white hover:bg-red-50">
                                <span class="group-hover:-translate-x-1 transition-transform">←</span> Quay Lại
                            </a>
                            <button type="submit" 
                                    class="flex-[2] bg-gradient-to-r from-red-600 via-pink-600 to-red-600 bg-size-200 hover:bg-right text-white font-bold py-4 px-6 rounded-xl transition-all duration-500 shadow-lg hover:shadow-red-500/50 hover:scale-[1.02] text-lg uppercase tracking-wider flex items-center justify-center gap-2">
                                <span>Xác Nhận Thanh Toán</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
