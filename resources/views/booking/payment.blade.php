<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Xác Nhận Đặt Vé') }}
        </h2>
    </x-slot>

    <div class="payment-page">
        <div class="payment-container">

            <div class="payment-card">
                
                <h3 class="page-title">Thông Tin Đặt Vé</h3>

                <div class="payment-grid">
                    
                    <!-- Left Column: Movie Info -->
                    <div class="movie-column">
                        @if($showtime->movie->poster)
                            <img src="{{ Storage::url($showtime->movie->poster) }}" 
                                 class="movie-poster"
                                 alt="{{ $showtime->movie->title }}">
                        @endif
                        
                        <div class="movie-details">
                            <h4 class="movie-title">{{ $showtime->movie->title }}</h4>
                            <div class="movie-duration">{{ $showtime->movie->duration }} phút</div>
                            
                            <div class="info-box">
                                <div class="info-row">
                                    <span class="info-label">Rạp</span>
                                    <span class="info-value">{{ $showtime->room->cinema->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phòng</span>
                                    <span class="info-value">{{ $showtime->room->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Xuất chiếu</span>
                                    <span class="info-value highlight">{{ $showtime->start_time->format('H:i') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Ngày</span>
                                    <span class="info-value">{{ $showtime->start_time->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Booking Form -->
                    <div class="booking-column">
                        <form method="POST" action="{{ route('booking.checkout', $showtime) }}">
                            @csrf
                            <input type="hidden" name="seat_ids" value="{{ $seatIds }}">

                            <!-- Selected Seats -->
                            <div class="section-box seats-section">
                                <h4 class="section-label">GHẾ ĐÃ CHỌN</h4>
                                <div class="seats-list">
                                    @foreach($seats as $seat)
                                        <span class="seat-tag">{{ $seat->row }}{{ $seat->number }}</span>
                                    @endforeach
                                </div>
                                
                                <div class="total-box">
                                    <span class="total-label">Tổng cộng</span>
                                    @php
                                        $total = count($seats) * 100000;
                                    @endphp
                                    <span class="total-price">{{ number_format($total, 0, ',', '.') }} đ</span>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="section-box">
                                <h4 class="section-label">PHƯƠNG THỨC THANH TOÁN</h4>
                                
                                <div class="payment-methods">
                                    
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="cash" checked>
                                        <div class="option-content">
                                            <span class="option-icon">💵</span>
                                            <div class="option-text">
                                                <span class="option-name">Tiền Mặt</span>
                                                <span class="option-desc">Thanh toán tại quầy</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="vnpay">
                                        <div class="option-content">
                                            <span class="option-icon vnpay">VNP</span>
                                            <div class="option-text">
                                                <span class="option-name">VNPay</span>
                                                <span class="option-desc">Quét mã QR</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="momo">
                                        <div class="option-content">
                                            <span class="option-icon momo">Mo</span>
                                            <div class="option-text">
                                                <span class="option-name">MoMo</span>
                                                <span class="option-desc">Ví điện tử</span>
                                            </div>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="actions-box">
                                <a href="{{ route('booking.show', $showtime) }}" class="btn-back">
                                    ← Quay lại
                                </a>
                                
                                <button type="submit" class="btn-submit">
                                    XÁC NHẬN THANH TOÁN
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

<style>
.payment-page {
    padding: 40px 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

.payment-container {
    max-width: 1200px;
    margin: 0 auto;
}

.payment-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
}

.page-title {
    font-size: 28px;
    font-weight: bold;
    color: #111;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

/* Grid Layout */
.payment-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 40px;
}

@media (max-width: 968px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }
}

/* Movie Column */
.movie-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.movie-poster {
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.movie-details {
}

.movie-title {
    font-size: 26px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 8px;
}

.movie-duration {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}

.info-box {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e5e5e5;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-size: 14px;
}

.info-value {
    font-weight: 600;
    color: #111;
}

.info-value.highlight {
    color: #e50914;
    font-weight: bold;
}

/* Booking Column */
.booking-column {
}

.section-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.section-label {
    font-size: 12px;
    font-weight: bold;
    color: #666;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

/* Seats Section */
.seats-section {
    background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
    border: 2px solid #ffe5e5;
}

.seats-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.seat-tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: #e50914;
    color: #fff;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(229, 9, 20, 0.25);
}

.total-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 2px solid #ffe0e0;
}

.total-label {
    font-size: 16px;
    color: #666;
}

.total-price {
    font-size: 32px;
    font-weight: bold;
    color: #e50914;
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: block;
    position: relative;
    cursor: pointer;
}

.payment-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.option-content {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.2s;
}

.payment-option:hover .option-content {
    border-color: #e50914;
    background: #fff5f5;
}

.payment-option input:checked + .option-content {
    border-color: #e50914;
    background: #fff5f5;
    box-shadow: 0 2px 8px rgba(229, 9, 20, 0.15);
}

.option-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: #f0f0f0;
    border-radius: 8px;
}

.option-icon.vnpay {
    background: #e3f2fd;
    color: #1976d2;
    font-weight: bold;
    font-size: 16px;
}

.option-icon.momo {
    background: #fce4ec;
    color: #c2185b;
    font-weight: bold;
    font-size: 16px;
}

.option-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.option-name {
    font-weight: bold;
    color: #111;
    font-size: 15px;
}

.option-desc {
    font-size: 13px;
    color: #666;
}

/* Actions */
.actions-box {
    display: flex;
    gap: 16px;
    padding-top: 8px;
}

.btn-back {
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 600;
    color: #666;
    background: #f0f0f0;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-back:hover {
    background: #e0e0e0;
    color: #111;
}

.btn-submit {
    flex: 1;
    background: #e50914;
    color: #fff;
    padding: 16px 32px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
}

.btn-submit:hover {
    background: #c40812;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(229, 9, 20, 0.4);
}

@media (max-width: 768px) {
    .payment-card {
        padding: 24px;
    }
    
    .actions-box {
        flex-direction: column;
    }
    
    .btn-back {
        text-align: center;
    }
}
</style>
</x-app-layout>
