<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select Seats for') }} {{ $showtime->movie->title }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ $showtime->room->cinema->name }} - {{ $showtime->room->name }} - {{ $showtime->start_time->format('d/m/Y H:i') }}
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-8 text-center">
                    <div class="w-full h-8 bg-gray-400 rounded-lg mb-2 mx-auto" style="max-width: 500px;"></div>
                    <span class="text-sm text-gray-500">SCREEN</span>
                </div>

                <div class="flex flex-col items-center gap-4">
                    @php
                        // Group seats by row
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @foreach($rows as $row => $seats)
                        <div class="flex gap-2">
                            <div class="w-8 flex items-center justify-center font-bold">{{ $row }}</div>
                            @foreach($seats as $seat)
                                @php
                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                    // Should also check if locked by others via API or initial load check
                                    $seatClass = $isBooked ? 'bg-gray-500 cursor-not-allowed' : 'bg-white border-2 border-gray-300 hover:border-blue-500 cursor-pointer seat-item';
                                @endphp
                                <button 
                                    type="button"
                                    data-id="{{ $seat->id }}"
                                    data-row="{{ $seat->row }}"
                                    data-number="{{ $seat->number }}"
                                    class="w-10 h-10 rounded-t-lg {{ $seatClass }} flex items-center justify-center text-sm font-semibold"
                                    {{ $isBooked ? 'disabled' : '' }}
                                    onclick="toggleSeat(this)"
                                >
                                    {{ $seat->number }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-between items-center border-t pt-4">
                    <div>
                        <p>Selected Seats: <span id="selected-seats-display" class="font-bold">None</span></p>
                        <p class="hidden">Total: <span id="total-price">0</span> VND</p>
                    </div>
                    <form id="checkout-form" action="{{ route('booking.checkout', $showtime) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seat_ids" id="seat-ids-input">
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="return prepareCheckout()">
                            Proceed to Payment
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        const showtimeId = {{ $showtime->id }};
        const selectedSeats = new Set(); // Stores labels e.g. A1
        const selectedSeatIds = new Set(); // Stores IDs e.g. 5
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function prepareCheckout() {
             if (selectedSeatIds.size === 0) {
                 alert('Please select at least one seat.');
                 return false;
             }
             document.getElementById('seat-ids-input').value = Array.from(selectedSeatIds).join(',');
             return true;
        }

        function toggleSeat(element) {
            const seatId = element.getAttribute('data-id');
            const seatLabel = element.getAttribute('data-row') + element.getAttribute('data-number');

            if (element.classList.contains('bg-green-500')) {
                // Unlock
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
                        element.classList.remove('bg-green-500', 'text-white');
                        element.classList.add('bg-white', 'border-gray-300');
                        selectedSeats.delete(seatLabel);
                        selectedSeatIds.delete(seatId);
                        updateDisplay();
                    }
                });
            } else {
                // Lock
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
                        element.classList.remove('bg-white', 'border-gray-300');
                        element.classList.add('bg-green-500', 'text-white');
                        selectedSeats.add(seatLabel);
                        selectedSeatIds.add(seatId);
                        updateDisplay();
                    } else {
                        alert(data.message);
                        if(data.message.includes('already')) {
                            element.classList.add('bg-gray-500');
                            element.disabled = true;
                        }
                    }
                });
            }
        }

        function updateDisplay() {
            const display = document.getElementById('selected-seats-display');
            if (selectedSeats.size > 0) {
                display.innerText = Array.from(selectedSeats).join(', ');
            } else {
                display.innerText = 'None';
            }
        }
    </script>
</x-app-layout>
