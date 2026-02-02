<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chọn Ghế Ngồi') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            <strong>{{ $showtime->movie->title }}</strong>
            - {{ $showtime->room->cinema->name }}
            - {{ $showtime->room->name }}
            - {{ $showtime->start_time->format('H:i, d/m/Y') }}
        </p>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-black">
        <div class="max-w-6xl mx-auto px-4">

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl p-8">

                <!-- SCREEN -->
                <div class="mb-12 text-center">
                    <div class="relative mx-auto max-w-2xl">
                        <!-- Screen Visual -->
                        <div class="h-12 border-t-4 border-red-500 rounded-[50%] shadow-[0_-10px_20px_rgba(239,68,68,0.3)] w-full"></div>
                        <p class="mt-4 tracking-[0.3em] font-bold text-gray-400 text-sm uppercase">
                            Màn Hình
                        </p>
                    </div>
                </div>

                <!-- LEGEND -->
                <div class="inline-flex justify-center gap-6 md:gap-12 flex-wrap mb-10 bg-white shadow-sm p-4 rounded-full border border-gray-200 mx-auto">

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 border-2 border-gray-400 rounded bg-white"></div>
                        <span class="text-sm font-bold text-gray-600">Trống</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-red-600 rounded border border-red-700 shadow-sm"></div>
                        <span class="text-sm font-bold text-gray-600">Đang chọn</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-gray-300 rounded cursor-not-allowed"></div>
                        <span class="text-sm font-bold text-gray-600">Đã đặt</span>
                    </div>

                </div>

                <!-- SEATS -->
                <div class="flex flex-col items-center gap-4 mb-10 overflow-x-auto pb-4">

                    @php
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @foreach($rows as $row => $seats)

                        <div class="flex items-center gap-4 flex-nowrap">

                            <!-- ROW LABEL -->
                            <div class="w-8 text-xl font-black text-gray-300">
                                {{ $row }}
                            </div>

                            <!-- SEATS -->
                            <div class="flex gap-2 md:gap-3">

                                @foreach($seats as $seat)

                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);

                                        $seatClass = $isBooked
                                            ? 'bg-gray-200 text-transparent cursor-not-allowed border border-gray-200'
                                            : 'bg-white border-2 border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md cursor-pointer';
                                    @endphp

                                    <button
                                        type="button"
                                        data-id="{{ $seat->id }}"
                                        data-row="{{ $seat->row }}"
                                        data-number="{{ $seat->number }}"
                                        onclick="toggleSeat(this)"
                                        {{ $isBooked ? 'disabled' : '' }}
                                        class="seat-btn w-8 h-8 md:w-10 md:h-10 rounded-lg text-sm md:text-base font-bold transition-all duration-200 {{ $seatClass }}"
                                    >
                                        {{ $seat->number }}
                                    </button>

                                @endforeach

                            </div>
                        </div>

                    @endforeach

                </div>

                <!-- SUMMARY -->
                <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">

                    <div>
                        <p class="text-gray-500 font-medium mb-1">Ghế đã chọn:</p>
                        <p id="selected-seats-display" class="text-2xl font-bold text-red-600 min-h-[2rem]">
                            Chưa chọn ghế
                        </p>

                        <p class="mt-2 text-lg">
                            Tổng tiền:
                            <span id="total-price" class="font-bold text-gray-900">0</span> <span class="text-sm text-gray-500">VND</span>
                        </p>
                    </div>

                    <!-- PAY -->
                    <form action="{{ route('booking.payment', $showtime) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seat_ids" id="seat-ids-input">

                        <button onclick="return prepareCheckout()"
                            class="bg-red-600 hover:bg-red-700
                                text-white font-bold
                                px-10 py-4 rounded-xl
                                shadow-lg shadow-red-500/30
                                hover:shadow-red-500/50
                                transform hover:-translate-y-1
                                transition-all duration-200
                                flex items-center gap-2">
                            <span>THANH TOÁN</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>


                    </form>

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
const maxSeats = 4;

function prepareCheckout() {

    if (selectedSeatIds.size === 0) {
        alert("Bạn chưa chọn ghế!");
        return false;
    }

    document.getElementById('seat-ids-input').value =
        Array.from(selectedSeatIds).join(',');

    return true;
}

function toggleSeat(el) {

    const seatId = el.dataset.id;
    const label = el.dataset.row + el.dataset.number;

    // Check if selected (class bg-red-600)
    if (el.classList.contains('bg-red-600')) {
        unlockSeat(el, seatId, label);
    } else {
        if (selectedSeatIds.size >= maxSeats) {
            alert("Tối đa 4 ghế!");
            return;
        }
        lockSeat(el, seatId, label);
    }
}

function lockSeat(el, id, label) {

    fetch(`/booking/${showtimeId}/lock`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ seat_id: id })
    })

    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {

            // Apply SELECTED style
            el.className =
                "seat-btn w-8 h-8 md:w-10 md:h-10 rounded-lg text-sm md:text-base font-bold transition-all duration-200 bg-red-600 text-white border-2 border-red-600 shadow-lg scale-110";

            selectedSeats.add(label);
            selectedSeatIds.add(id);
            updateDisplay();

        } else {
            alert("Ghế đã có người chọn!");
        }

    });
}

function unlockSeat(el, id, label) {

    fetch(`/booking/${showtimeId}/unlock`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ seat_id: id })
    })

    .then(() => {

        // Apply UNSELECTED (available) style
        el.className =
            "seat-btn w-8 h-8 md:w-10 md:h-10 rounded-lg text-sm md:text-base font-bold transition-all duration-200 bg-white border-2 border-gray-300 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md cursor-pointer";

        selectedSeats.delete(label);
        selectedSeatIds.delete(id);
        updateDisplay();
    });
}

function updateDisplay() {

    const seatBox = document.getElementById("selected-seats-display");
    const priceBox = document.getElementById("total-price");

    if (selectedSeats.size > 0) {

        seatBox.innerText = [...selectedSeats].join(", ");
        priceBox.innerText =
            (selectedSeats.size * pricePerSeat).toLocaleString("vi-VN");

    } else {

        seatBox.innerText = "Chưa chọn ghế";
        priceBox.innerText = "0";
    }
}

</script>
</x-app-layout>
