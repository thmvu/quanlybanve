<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Xác Nhận Đặt Vé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Thông Tin Đặt Vé</h3>

                    <!-- Movie & Showtime Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex gap-6">
                            @if($showtime->movie->poster)
                                <img src="{{ Storage::url($showtime->movie->poster) }}" 
                                     class="w-32 h-48 object-cover rounded-lg shadow-lg"
                                     alt="{{ $showtime->movie->title }}">
                            @endif
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $showtime->movie->title }}
                                </h4>
                                <div class="space-y-1 text-gray-600 dark:text-gray-400">
                                    <p><strong>Rạp:</strong> {{ $showtime->room->cinema->name }}</p>
                                    <p><strong>Phòng:</strong> {{ $showtime->room->name }}</p>
                                    <p><strong>Suất chiếu:</strong> {{ $showtime->start_time->format('H:i - d/m/Y') }}</p>
                                    <p><strong>Thời lượng:</strong> {{ $showtime->movie->duration }} phút</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Seats -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-3">Ghế Đã Chọn</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($seats as $seat)
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold">
                                    {{ $seat->row }}{{ $seat->number }}
                                </span>
                            @endforeach
                        </div>
                        <p class="mt-3 text-gray-600 dark:text-gray-400">
                            Tổng số ghế: <strong>{{ count($seats) }}</strong>
                        </p>
                    </div>

                    <!-- Price Summary -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-3">Chi Tiết Giá</h4>
                        @php
                            $pricePerSeat = 100000;
                            $totalPrice = count($seats) * $pricePerSeat;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Giá vé ({{ count($seats) }} x {{ number_format($pricePerSeat, 0, ',', '.') }} VND)</span>
                                <span>{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-gray-100 pt-2 border-t">
                                <span>Tổng Cộng</span>
                                <span class="text-red-600">{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <form method="POST" action="{{ route('booking.checkout', $showtime) }}">
                        @csrf
                        <input type="hidden" name="seat_ids" value="{{ $seatIds }}">

                        <div class="mb-6">
                            <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-3">Phương Thức Thanh Toán</h4>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="cash" checked class="mr-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">Tiền Mặt</p>
                                        <p class="text-sm text-gray-500">Thanh toán tại quầy khi nhận vé</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="vnpay" class="mr-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">VNPay</p>
                                        <p class="text-sm text-gray-500">Thanh toán qua ví điện tử VNPay</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="payment_method" value="momo" class="mr-3">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">MoMo</p>
                                        <p class="text-sm text-gray-500">Thanh toán qua ví MoMo</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <a href="{{ route('booking.show', $showtime) }}" 
                               class="flex-1 text-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                ← Quay Lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Xác Nhận Đặt Vé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
