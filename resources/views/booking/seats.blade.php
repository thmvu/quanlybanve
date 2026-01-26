<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chọn Ghế Ngồi') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            <strong>{{ $showtime->movie->title }}</strong> - {{ $showtime->room->cinema->name }} - {{ $showtime->room->name }} - {{ $showtime->start_time->format('H:i, d/m/Y') }}
        </p>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                
                <!-- Screen -->
                <div class="mb-10 text-center">
                    <div class="relative mx-auto" style="max-width: 600px;">
                        <div class="h-3 bg-gradient-to-b from-gray-400 to-gray-300 rounded-t-3xl shadow-lg mb-3"></div>
                        <div class="text-sm font-bold text-gray-500 dark:text-gray-400 tracking-widest">MÀN HÌNH</div>
                    </div>
                </div>

                <!-- Seat Legend -->
                <div class="mb-8 flex flex-wrap justify-center gap-6 text-sm bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white border-2 border-gray-400 rounded-lg shadow-sm"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Còn Trống</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-green-500 rounded-lg shadow-md"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Đang Chọn</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gray-400 rounded-lg shadow-sm"></div>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Đã Đặt</span>
                    </div>
                </div>

                <!-- Seats Grid -->
                <div class="flex flex-col items-center gap-3 mb-8">
                    @php
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @if($rows->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">Phòng chiếu chưa có ghế. Vui lòng liên hệ quản trị viên.</p>
                        </div>
                    @else
                        @foreach($rows as $row => $seats)
                            <div class="flex gap-2 items-center">
                                <!-- Row Label -->
                                <div class="w-8 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300 text-lg">
                                    {{ $row }}
                                </div>
                                
                                <!-- Seats in Row -->
                                <div class="flex gap-2">
                                    @foreach($seats as $seat)
                                        @php
                                            $isBooked = in_array($seat->id, $bookedSeatIds);
                                            $seatClass = $isBooked 
                                                ? 'bg-gray-400 cursor-not-allowed text-white' 
                                                : 'bg-white border-2 border-gray-400 hover:border-blue-500 hover:bg-blue-50 cursor-pointer seat-item text-gray-800';
                                        @endphp
                                        <button 
                                            type="button"
                                            data-id="{{ $seat->id }}"
                                            data-row="{{ $seat->row }}"
                                            data-number="{{ $seat->number }}"
                                            class="w-12 h-12 rounded-lg {{ $seatClass }} flex items-center justify-center text-sm font-bold transition-all duration-200 shadow-sm hover:shadow-md {{ $isBooked ? '' : 'hover:scale-105' }}"
                                            {{ $isBooked ? 'disabled' : '' }}
                                            onclick="toggleSeat(this)"
                                        >
                                            <span class="seat-number">{{ $seat->number }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Summary & Checkout -->
                <div class="mt-8 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-center md:text-left">
                            <p class="text-gray-600 dark:text-gray-400 mb-1">Ghế đã chọn:</p>
                            <p id="selected-seats-display" class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                Chưa chọn ghế
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Giá: <span id="total-price" class="font-semibold text-red-600">0</span> VND
                            </p>
                        </div>
                        
                        <form id="checkout-form" action="{{ route('booking.payment', $showtime) }}" method="POST">
                            @csrf
                            <input type="hidden" name="seat_ids" id="seat-ids-input">
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    onclick="return prepareCheckout()">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Tiếp Tục Thanh Toán
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const showtimeId = {{ $showtime->id }};
        const selectedSeats = new Set();
        const selectedSeatIds = new Set();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const pricePerSeat = 100000;
        const maxSeats = 4; // Giới hạn tối đa 4 ghế

        function prepareCheckout() {
             if (selectedSeatIds.size === 0) {
                 alert('Vui lòng chọn ít nhất một ghế!');
                 return false;
             }
             document.getElementById('seat-ids-input').value = Array.from(selectedSeatIds).join(',');
             return true;
        }

        function toggleSeat(element) {
            const seatId = element.getAttribute('data-id');
            const seatLabel = element.getAttribute('data-row') + element.getAttribute('data-number');

            // Check if seat is already selected (green)
            if (element.classList.contains('bg-green-500')) {
                // Unlock - Bỏ chọn ghế
                fetch(`/booking/${showtimeId}/unlock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ seat_id: seatId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        // Đổi từ xanh về trắng - GIỮ SỐ GHẾ
                        element.classList.remove('bg-green-500', 'text-white', 'shadow-lg', 'scale-105');
                        element.classList.add('bg-white', 'border-2', 'border-gray-400', 'text-gray-800');
                        selectedSeats.delete(seatLabel);
                        selectedSeatIds.delete(seatId);
                        updateDisplay();
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            } else {
                // Check if max seats reached
                if (selectedSeatIds.size >= maxSeats) {
                    alert(`Bạn chỉ có thể chọn tối đa ${maxSeats} ghế!`);
                    return;
                }

                // Lock - Chọn ghế
                fetch(`/booking/${showtimeId}/lock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ seat_id: seatId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        // Đổi từ trắng sang xanh - GIỮ SỐ GHẾ
                        element.classList.remove('bg-white', 'border-2', 'border-gray-400', 'text-gray-800', 'hover:border-blue-500', 'hover:bg-blue-50');
                        element.classList.add('bg-green-500', 'text-white', 'shadow-lg', 'scale-105');
                        selectedSeats.add(seatLabel);
                        selectedSeatIds.add(seatId);
                        updateDisplay();
                    } else {
                        alert(data.message || 'Ghế này đã được người khác chọn!');
                        if(data.message && data.message.includes('already')) {
                            // Đổi sang xám (đã đặt)
                            element.classList.remove('bg-white', 'border-2', 'border-gray-400', 'hover:border-blue-500', 'hover:bg-blue-50');
                            element.classList.add('bg-gray-400', 'text-white', 'cursor-not-allowed');
                            element.disabled = true;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                });
            }
        }

        function updateDisplay() {
            const display = document.getElementById('selected-seats-display');
            const priceDisplay = document.getElementById('total-price');
            
            if (selectedSeats.size > 0) {
                display.innerText = Array.from(selectedSeats).sort().join(', ');
                const total = selectedSeats.size * pricePerSeat;
                priceDisplay.innerText = total.toLocaleString('vi-VN');
            } else {
                display.innerText = 'Chưa chọn ghế';
                priceDisplay.innerText = '0';
            }
        }
    </script>
</x-app-layout>
