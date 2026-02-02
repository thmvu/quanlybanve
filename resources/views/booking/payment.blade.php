<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Xác Nhận Đặt Vé') }}
        </h2>
    </x-slot>

    <div class="trang-thanhtoan">
        <div class="khung-thanhtoan">

            <div class="the-thanhtoan">
                
                <h3 class="tieude-trang">Thông Tin Đặt Vé</h3>

                <div class="luoi-thanhtoan">
                    
                    <!-- Cột trái: Thông tin phim -->
                    <div class="cot-phim">
                        @if($showtime->movie->poster)
                            <img src="{{ Storage::url($showtime->movie->poster) }}" 
                                 class="poster-phim"
                                 alt="{{ $showtime->movie->title }}">
                        @endif
                        
                        <div class="chitiet-phim">
                            <h4 class="ten-phim">{{ $showtime->movie->title }}</h4>
                            <div class="thoiluong-phim">{{ $showtime->movie->duration }} phút</div>
                            
                            <div class="hop-thongtin">
                                <div class="dong-thongtin">
                                    <span class="nhan-tt">Rạp</span>
                                    <span class="giatri-tt">{{ $showtime->room->cinema->name }}</span>
                                </div>
                                <div class="dong-thongtin">
                                    <span class="nhan-tt">Phòng</span>
                                    <span class="giatri-tt">{{ $showtime->room->name }}</span>
                                </div>
                                <div class="dong-thongtin">
                                    <span class="nhan-tt">Xuất chiếu</span>
                                    <span class="giatri-tt noibat">{{ $showtime->start_time->format('H:i') }}</span>
                                </div>
                                <div class="dong-thongtin">
                                    <span class="nhan-tt">Ngày</span>
                                    <span class="giatri-tt">{{ $showtime->start_time->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột phải: Form đặt vé -->
                    <div class="cot-datve">
                        <form method="POST" action="{{ route('booking.checkout', $showtime) }}">
                            @csrf
                            <input type="hidden" name="seat_ids" value="{{ $seatIds }}">

                            <!-- Ghế đã chọn -->
                            <div class="hop-phan phan-ghe">
                                <h4 class="nhan-phan">GHẾ ĐÃ CHỌN</h4>
                                <div class="danhsach-ghe">
                                    @foreach($seats as $seat)
                                        <span class="the-ghe">{{ $seat->row }}{{ $seat->number }}</span>
                                    @endforeach
                                </div>
                                
                                <div class="hop-tongcong">
                                    <span class="nhan-tongcong">Tổng cộng</span>
                                    @php
                                        $tongTien = count($seats) * 100000;
                                    @endphp
                                    <span class="gia-tongcong">{{ number_format($tongTien, 0, ',', '.') }} đ</span>
                                </div>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="hop-phan">
                                <h4 class="nhan-phan">PHƯƠNG THỨC THANH TOÁN</h4>
                                
                                <div class="cac-phuongthuc">
                                    
                                    <label class="lua-chon-tt">
                                        <input type="radio" name="payment_method" value="cash" checked>
                                        <div class="noidung-luachon">
                                            <span class="bieu-tuong">💵</span>
                                            <div class="chu-luachon">
                                                <span class="ten-luachon">Tiền Mặt</span>
                                                <span class="mota-luachon">Thanh toán tại quầy</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="lua-chon-tt">
                                        <input type="radio" name="payment_method" value="vnpay">
                                        <div class="noidung-luachon">
                                            <span class="bieu-tuong vnpay">VNP</span>
                                            <div class="chu-luachon">
                                                <span class="ten-luachon">VNPay</span>
                                                <span class="mota-luachon">Quét mã QR</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="lua-chon-tt">
                                        <input type="radio" name="payment_method" value="momo">
                                        <div class="noidung-luachon">
                                            <span class="bieu-tuong momo">Mo</span>
                                            <div class="chu-luachon">
                                                <span class="ten-luachon">MoMo</span>
                                                <span class="mota-luachon">Ví điện tử</span>
                                            </div>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <!-- Nút bấm -->
                            <div class="hop-nutbam">
                                <a href="{{ route('booking.show', $showtime) }}" class="nut-quaylai">
                                    ← Quay lại
                                </a>
                                
                                <button type="submit" class="nut-xacnhan">
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
.trang-thanhtoan {
    padding: 40px 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

.khung-thanhtoan {
    max-width: 1200px;
    margin: 0 auto;
}

.the-thanhtoan {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
}

.tieude-trang {
    font-size: 28px;
    font-weight: bold;
    color: #111;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

/* Lưới layout */
.luoi-thanhtoan {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 40px;
}

@media (max-width: 968px) {
    .luoi-thanhtoan {
        grid-template-columns: 1fr;
    }
}

/* Cột phim */
.cot-phim {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.poster-phim {
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.chitiet-phim {
}

.ten-phim {
    font-size: 26px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 8px;
}

.thoiluong-phim {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}

.hop-thongtin {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.dong-thongtin {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e5e5e5;
}

.dong-thongtin:last-child {
    border-bottom: none;
}

.nhan-tt {
    color: #666;
    font-size: 14px;
}

.giatri-tt {
    font-weight: 600;
    color: #111;
}

.giatri-tt.noibat {
    color: #e50914;
    font-weight: bold;
}

/* Cột đặt vé */
.cot-datve {
}

.hop-phan {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.nhan-phan {
    font-size: 12px;
    font-weight: bold;
    color: #666;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

/* Phần ghế */
.phan-ghe {
    background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
    border: 2px solid #ffe5e5;
}

.danhsach-ghe {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.the-ghe {
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

.hop-tongcong {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 2px solid #ffe0e0;
}

.nhan-tongcong {
    font-size: 16px;
    color: #666;
}

.gia-tongcong {
    font-size: 32px;
    font-weight: bold;
    color: #e50914;
}

/* Phương thức thanh toán */
.cac-phuongthuc {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.lua-chon-tt {
    display: block;
    position: relative;
    cursor: pointer;
}

.lua-chon-tt input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.noidung-luachon {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.2s;
}

.lua-chon-tt:hover .noidung-luachon {
    border-color: #e50914;
    background: #fff5f5;
}

.lua-chon-tt input:checked + .noidung-luachon {
    border-color: #e50914;
    background: #fff5f5;
    box-shadow: 0 2px 8px rgba(229, 9, 20, 0.15);
}

.bieu-tuong {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: #f0f0f0;
    border-radius: 8px;
}

.bieu-tuong.vnpay {
    background: #e3f2fd;
    color: #1976d2;
    font-weight: bold;
    font-size: 16px;
}

.bieu-tuong.momo {
    background: #fce4ec;
    color: #c2185b;
    font-weight: bold;
    font-size: 16px;
}

.chu-luachon {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.ten-luachon {
    font-weight: bold;
    color: #111;
    font-size: 15px;
}

.mota-luachon {
    font-size: 13px;
    color: #666;
}

/* Nút bấm */
.hop-nutbam {
    display: flex;
    gap: 16px;
    padding-top: 8px;
}

.nut-quaylai {
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 600;
    color: #666;
    background: #f0f0f0;
    transition: all 0.2s;
    text-decoration: none;
}

.nut-quaylai:hover {
    background: #e0e0e0;
    color: #111;
}

.nut-xacnhan {
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

.nut-xacnhan:hover {
    background: #c40812;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(229, 9, 20, 0.4);
}

@media (max-width: 768px) {
    .the-thanhtoan {
        padding: 24px;
    }
    
    .hop-nutbam {
        flex-direction: column;
    }
    
    .nut-quaylai {
        text-align: center;
    }
}
</style>
</x-app-layout>
