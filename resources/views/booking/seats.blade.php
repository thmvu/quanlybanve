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
                    <div class="relative mx-auto max-w-xl">
                        <div class="h-3 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 rounded-full shadow-lg"></div>
                        <p class="mt-2 tracking-widest font-bold text-gray-500 dark:text-gray-400">
                            MÀN HÌNH
                        </p>
                    </div>
                </div>

                <!-- LEGEND -->
                <div class="flex justify-center gap-8 flex-wrap mb-10 bg-gray-100 dark:bg-gray-800 p-4 rounded-xl">

                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white border-2 border-indigo-400 rounded-lg"></div>
                        <span>Còn trống</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-green-500 rounded-lg shadow"></div>
                        <span>Đang chọn</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gray-500 rounded-lg"></div>
                        <span>Đã đặt</span>
                    </div>

                </div>

                <!-- SEATS -->
                <div class="flex flex-col items-center gap-4 mb-10">

                    @php
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @foreach($rows as $row => $seats)

                        <div class="flex items-center gap-3">

                            <!-- ROW -->
                            <div class="w-8 text-lg font-bold text-indigo-500">
                                {{ $row }}
                            </div>

                            <!-- SEATS -->
                            <div class="flex gap-2">

                                @foreach($seats as $seat)

                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);

                                        $seatClass = $isBooked
                                            ? 'bg-gray-500 text-white cursor-not-allowed'
                                            : 'bg-white border-2 border-indigo-400 hover:bg-indigo-100 hover:border-indigo-600 cursor-pointer text-gray-800';
                                    @endphp

                                    <button
                                        type="button"
                                        data-id="{{ $seat->id }}"
                                        data-row="{{ $seat->row }}"
                                        data-number="{{ $seat->number }}"
                                        onclick="toggleSeat(this)"
                                        {{ $isBooked ? 'disabled' : '' }}
                                        class="seat-btn w-12 h-12 rounded-xl font-bold shadow-md transition-all duration-200 hover:scale-110 {{ $seatClass }}"
                                    >
                                        {{ $seat->number }}
                                    </button>

                                @endforeach

                            </div>
                        </div>

                    @endforeach

                </div>

                <!-- SUMMARY -->
                <div class="border-t pt-6 flex flex-col md:flex-row justify-between items-center gap-6">

                    <div>
                        <p class="text-gray-500">Ghế đã chọn:</p>
                        <p id="selected-seats-display" class="text-2xl font-bold text-indigo-600">
                            Chưa chọn ghế
                        </p>

                        <p class="mt-1">
                            Tổng tiền:
                            <span id="total-price" class="font-bold text-red-600">0</span> VND
                        </p>
                    </div>

                    <!-- PAY -->
                    <form action="{{ route('booking.payment', $showtime) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seat_ids" id="seat-ids-input">

                        <button onclick="return prepareCheckout()"
                            class="bg-red-600 hover:bg-red-700
                                text-white font-bold
                                px-12 py-4 rounded-xl
                                shadow-[0_0_20px_rgba(239,68,68,0.6)]
                                hover:shadow-[0_0_35px_rgba(239,68,68,0.9)]
                                transition-all duration-200
                                hover:scale-105">
                            THANH TOÁN →
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

    if (el.classList.contains('bg-green-500')) {

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

            el.className =
                "seat-btn w-12 h-12 rounded-xl font-bold shadow-lg bg-green-500 text-white scale-110";

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

        el.className =
            "seat-btn w-12 h-12 rounded-xl font-bold shadow-md bg-white border-2 border-indigo-400 text-gray-800";

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
