<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Chọn Ghế Ngồi') }}
        </h2>
    </x-slot>

    <div class="trang-chonghe">
        <div class="khung-chonghe">

            @if(session('error'))
                <div class="thongbao-loi">
                    {{ session('error') }}
                </div>
            @endif

            <div class="the-chonghe">

                <!-- Thông tin phim -->
                <div class="dauphim-header">
                    <h2>{{ $showtime->movie->title }}</h2>
                    <p class="thongtin-phim">
                        {{ $showtime->room->cinema->name }} • {{ $showtime->room->name }} • 
                        {{ $showtime->start_time->format('H:i, d/m/Y') }}
                    </p>
                </div>

                <!-- Màn hình -->
                <div class="phan-manhinh">
                    <div class="thanh-manhinh"></div>
                    <p class="nhan-manhinh">MÀN HÌNH</p>
                </div>

                <!-- Chú thích -->
                <div class="hop-chuathich">
                    <div class="muc-chuathich">
                        <div class="ghe-mau controng"></div>
                        <span>Trống</span>
                    </div>
                    <div class="muc-chuathich">
                        <div class="ghe-mau dangchon"></div>
                        <span>Đang chọn</span>
                    </div>
                    <div class="muc-chuathich">
                        <div class="ghe-mau dadat"></div>
                        <span>Đã đặt</span>
                    </div>
                </div>

                <!-- Lưới ghế -->
                <div class="luoi-ghe">
                    @php
                        $rows = $showtime->room->seats->groupBy('row');
                    @endphp

                    @foreach($rows as $row => $seats)
                        <div class="hang-ghe">
                            <div class="nhan-hang">{{ $row }}</div>

                            <div class="danh-sach-ghe">
                                @foreach($seats as $seat)
                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);
                                        $seatClass = $isBooked ? 'nut-ghe dadat' : 'nut-ghe';
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

                <!-- Tổng kết & thanh toán -->
                <div class="phan-thanhtoan">
                    <div class="hop-tongket">
                        <p class="nhan-tongket">Ghế đã chọn:</p>
                        <p id="hienthi-ghe" class="ghe-dachon">Chưa chọn ghế</p>
                        <p class="gia-tong">
                            Tổng tiền: <span id="tong-gia">0</span> VND
                        </p>
                    </div>

                    <form action="{{ route('booking.payment', $showtime) }}" method="POST">
                        @csrf
                        <input type="hidden" name="seat_ids" id="input-id-ghe">
                        <button onclick="return chuanBiThanhToan()" class="nut-thanhtoan">
                            THANH TOÁN →
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

<style>
.trang-chonghe {
    padding: 40px 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

.khung-chonghe {
    max-width: 1000px;
    margin: 0 auto;
}

.the-chonghe {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
}

.thongbao-loi {
    background: #fee;
    border: 1px solid #fcc;
    color: #c00;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* Đầu phim */
.dauphim-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.dauphim-header h2 {
    font-size: 32px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 8px;
}

.thongtin-phim {
    color: #666;
    font-size: 15px;
}

/* Màn hình */
.phan-manhinh {
    margin-bottom: 40px;
    text-align: center;
}

.thanh-manhinh {
    width: 70%;
    height: 12px;
    background: linear-gradient(to bottom, #e50914, #ff6b6b);
    margin: 0 auto 12px;
    border-radius: 0 0 50% 50%;
    box-shadow: 0 8px 20px rgba(229, 9, 20, 0.3);
}

.nhan-manhinh {
    font-size: 12px;
    font-weight: bold;
    color: #999;
    letter-spacing: 3px;
}

/* Chú thích */
.hop-chuathich {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.muc-chuathich {
    display: flex;
    align-items: center;
    gap: 8px;
}

.muc-chuathich span {
    font-size: 14px;
    font-weight: 600;
    color: #555;
}

.ghe-mau {
    width: 32px;
    height: 32px;
    border-radius: 6px;
}

.ghe-mau.controng {
    background: #fff;
    border: 2px solid #ddd;
}

.ghe-mau.dangchon {
    background: #e50914;
    border: 2px solid #c40812;
}

.ghe-mau.dadat {
    background: #ccc;
    border: 2px solid #aaa;
}

/* Lưới ghế */
.luoi-ghe {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    margin-bottom: 40px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.hang-ghe {
    display: flex;
    align-items: center;
    gap: 16px;
}

.nhan-hang {
    width: 30px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: #aaa;
}

.danh-sach-ghe {
    display: flex;
    gap: 8px;
}

.nut-ghe {
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

.nut-ghe:hover:not(.dadat):not(.dangchon) {
    border-color: #e50914;
    background: #fff5f5;
    transform: scale(1.1);
}

.nut-ghe.dangchon {
    background: #e50914;
    border-color: #c40812;
    color: #fff;
    transform: scale(1.1);
}

.nut-ghe.dadat {
    background: #e0e0e0;
    border-color: #bbb;
    color: transparent;
    cursor: not-allowed;
}

/* Thanh toán */
.phan-thanhtoan {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
    gap: 20px;
    flex-wrap: wrap;
}

.hop-tongket {
    flex: 1;
}

.nhan-tongket {
    font-size: 14px;
    color: #666;
    margin-bottom: 6px;
}

.ghe-dachon {
    font-size: 24px;
    font-weight: bold;
    color: #e50914;
    margin-bottom: 10px;
}

.gia-tong {
    font-size: 16px;
    color: #333;
}

.gia-tong span {
    font-weight: bold;
    color: #000;
}

.nut-thanhtoan {
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

.nut-thanhtoan:hover {
    background: #c40812;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(229, 9, 20, 0.4);
}

@media (max-width: 768px) {
    .the-chonghe {
        padding: 20px;
    }
    
    .phan-thanhtoan {
        flex-direction: column;
        align-items: stretch;
    }
    
    .nut-thanhtoan {
        width: 100%;
    }
}
</style>

<script>
const showtimeId = {{ $showtime->id }};
const gheChon = new Set();
const idGheChon = new Set();
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const giaMotGhe = 100000;
const soGheToiDa = 4;

function chuanBiThanhToan() {
    if (idGheChon.size === 0) {
        alert("Bạn chưa chọn ghế!");
        return false;
    }
    document.getElementById('input-id-ghe').value = Array.from(idGheChon).join(',');
    return true;
}

function toggleSeat(el) {
    const idGhe = el.dataset.id;
    const nhan = el.dataset.row + el.dataset.number;

    if (el.classList.contains('dangchon')) {
        moKhoaGhe(el, idGhe, nhan);
    } else {
        if (idGheChon.size >= soGheToiDa) {
            alert("Tối đa 4 ghế!");
            return;
        }
        khoaGhe(el, idGhe, nhan);
    }
}

function khoaGhe(el, id, nhan) {
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
            el.classList.add('dangchon');
            gheChon.add(nhan);
            idGheChon.add(id);
            capNhatHienThi();
        } else {
            alert("Ghế đã có người chọn!");
        }
    });
}

function moKhoaGhe(el, id, nhan) {
    fetch(`/booking/${showtimeId}/unlock`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ seat_id: id })
    })
    .then(() => {
        el.classList.remove('dangchon');
        gheChon.delete(nhan);
        idGheChon.delete(id);
        capNhatHienThi();
    });
}

function capNhatHienThi() {
    const hopGhe = document.getElementById("hienthi-ghe");
    const hopGia = document.getElementById("tong-gia");

    if (gheChon.size > 0) {
        hopGhe.innerText = [...gheChon].join(", ");
        hopGia.innerText = (gheChon.size * giaMotGhe).toLocaleString("vi-VN");
    } else {
        hopGhe.innerText = "Chưa chọn ghế";
        hopGia.innerText = "0";
    }
}
</script>
</x-app-layout>
