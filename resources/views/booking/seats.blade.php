<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Chọn Ghế Ngồi') }}
        </h2>
    </x-slot>

    <div class="seats-page">
        <div class="seats-container">

            @if(session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="seats-card">

                <!-- Movie Info Header -->
                <div class="movie-header">
                    <h2>{{ $showtime->movie->title }}</h2>
                    <p class="movie-meta">
                        {{ $showtime->room->cinema->name }} • {{ $showtime->room->name }} • 
                        {{ $showtime->start_time->format('H:i, d/m/Y') }}
                    </p>
                </div>

                <!-- SCREEN -->
                <div class="screen-section">
                    <div class="screen-bar"></div>
                    <p class="screen-label">MÀN HÌNH</p>
                </div>

                <!-- LEGEND -->
                <div class="legend-box">
                    <div class="legend-item">
                        <div class="seat-demo available"></div>
                        <span>Trống</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-demo selected"></div>
                        <span>Đang chọn</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-demo booked"></div>
                        <span>Đã đặt</span>
                    </div>
                </div>

                <!-- SEATS GRID -->
                <div class="seats-grid">
                    @php
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @foreach($rows as $row => $seats)
                        <div class="seat-row">
                            <div class="row-label">{{ $row }}</div>

                            <div class="seat-list">
                                @foreach($seats as $seat)
                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);
                                        $seatClass = $isBooked ? 'seat-btn booked' : 'seat-btn';
                                    @endphp

                                    <button
                                        type="button"
                                        data-id="{{ $seat->id }}"
                                        data-row="{{ $seat->row }}"
                                        data-number="{{ $seat->number }}"
                                        onclick="toggleSeat(this)"
                                        {{ $isBooked ? 'disabled' : '' }}
                                        class="{{ $seatClass }}">
                                        {{ $seat->number }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- SUMMARY & CHECKOUT -->
                <div class="checkout-section">
                    <div class="summary-box">
                        <p class="summary-label">Ghế đã chọn:</p>
                        <p id="selected-seats-display" class="summary-seats">Chưa chọn ghế</p>
                        <p class="summary-price">
                            Tổng tiền: <span id="total-price">0</span> VND
                        </p>
                    </div>

                    <form action="{{ route('booking.payment', $showtime) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seat_ids" id="seat-ids-input">
                        <button onclick="return prepareCheckout()" class="btn-checkout">
                            THANH TOÁN →
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

<style>
.seats-page {
    padding: 40px 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

.seats-container {
    max-width: 1000px;
    margin: 0 auto;
}

.seats-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
}

.alert-error {
    background: #fee;
    border: 1px solid #fcc;
    color: #c00;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* Movie Header */
.movie-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.movie-header h2 {
    font-size: 32px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 8px;
}

.movie-meta {
    color: #666;
    font-size: 15px;
}

/* Screen */
.screen-section {
    margin-bottom: 40px;
    text-align: center;
}

.screen-bar {
    width: 70%;
    height: 12px;
    background: linear-gradient(to bottom, #e50914, #ff6b6b);
    margin: 0 auto 12px;
    border-radius: 0 0 50% 50%;
    box-shadow: 0 8px 20px rgba(229, 9, 20, 0.3);
}

.screen-label {
    font-size: 12px;
    font-weight: bold;
    color: #999;
    letter-spacing: 3px;
}

/* Legend */
.legend-box {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-item span {
    font-size: 14px;
    font-weight: 600;
    color: #555;
}

.seat-demo {
    width: 32px;
    height: 32px;
    border-radius: 6px;
}

.seat-demo.available {
    background: #fff;
    border: 2px solid #ddd;
}

.seat-demo.selected {
    background: #e50914;
    border: 2px solid #c40812;
}

.seat-demo.booked {
    background: #ccc;
    border: 2px solid #aaa;
}

/* Seats Grid */
.seats-grid {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.seat-row {
    display: flex;
    align-items: center;
    gap: 16px;
}

.row-label {
    width: 30px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: #aaa;
}

.seat-list {
    display: flex;
    gap: 8px;
}

.seat-btn {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid #ddd;
    background: #fff;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    color: #333;
}

.seat-btn:hover:not(.booked):not(.selected) {
    border-color: #e50914;
    background: #fff5f5;
    transform: scale(1.1);
}

.seat-btn.selected {
    background: #e50914;
    border-color: #c40812;
    color: #fff;
    transform: scale(1.1);
}

.seat-btn.booked {
    background: #e0e0e0;
    border-color: #bbb;
    color: transparent;
    cursor: not-allowed;
}

/* Checkout Section */
.checkout-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
    gap: 20px;
    flex-wrap: wrap;
}

.summary-box {
    flex: 1;
}

.summary-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 6px;
}

.summary-seats {
    font-size: 24px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 10px;
}

.summary-price {
    font-size: 16px;
    color: #333;
}

.summary-price span {
    font-weight: bold;
    color: #000;
}

.btn-checkout {
    background: #e50914;
    color: #fff;
    padding: 16px 48px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
}

.btn-checkout:hover {
    background: #c40812;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(229, 9, 20, 0.4);
}

@media (max-width: 768px) {
    .seats-card {
        padding: 20px;
    }
    
    .checkout-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-checkout {
        width: 100%;
    }
}
</style>

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
    document.getElementById('seat-ids-input').value = Array.from(selectedSeatIds).join(',');
    return true;
}

function toggleSeat(el) {
    const seatId = el.dataset.id;
    const label = el.dataset.row + el.dataset.number;

    if (el.classList.contains('selected')) {
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
            el.classList.add('selected');
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
        el.classList.remove('selected');
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
        priceBox.innerText = (selectedSeats.size * pricePerSeat).toLocaleString("vi-VN");
    } else {
        seatBox.innerText = "Chưa chọn ghế";
        priceBox.innerText = "0";
    }
}
</script>
</x-app-layout>
