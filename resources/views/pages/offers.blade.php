<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ưu Đãi & Khuyến Mãi') }}
        </h2>
    </x-slot>

    <section class="offers-section">
        <h2 class="offers-title">ƯU ĐÃI ĐẶC BIỆT</h2>
        
        <div class="offers-grid">
            <!-- Offer 1: Tuesday Special -->
            <div class="offer-card">
                <img src="{{ asset('images/tuesday_offer.png') }}" alt="Tuesday Special" class="offer-image">
                <div class="offer-content">
                    <h3 class="offer-title">Thứ 3 Vui Vẻ</h3>
                    <p class="offer-description">Giảm giá 50% cho tất cả các suất chiếu vào mỗi ngày Thứ 3 trong tuần. Đừng bỏ lỡ cơ hội xem phim với giá siêu ưu đãi!</p>
                    <a href="{{ route('home') }}" class="btn-offer">
                        Đặt vé ngay
                    </a>
                </div>
            </div>

            <!-- Offer 2: New Member -->
            <div class="offer-card">
                <img src="{{ asset('images/new_member_offer.png') }}" alt="New Member Offer" class="offer-image">
                <div class="offer-content">
                    <h3 class="offer-title">Thành Viên Mới</h3>
                    <p class="offer-description">Tặng ngay 1 bắp rang bơ và 1 nước ngọt miễn phí cho khách hàng đăng ký thành viên mới trong tháng này. Đăng ký ngay để nhận ưu đãi!</p>
                    <a href="{{ route('register') }}" class="btn-offer">
                        Đăng ký ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
    .offers-section {
        padding: 60px 40px;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .offers-title {
        text-align: center;
        font-size: 42px;
        font-weight: bold;
        color: #111;
        margin-bottom: 50px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .offers-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 40px;
    }

    .offer-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,.1);
        transition: .3s;
    }

    .offer-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 50px rgba(0,0,0,.15);
    }

    .offer-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        display: block;
    }

    .offer-content {
        padding: 32px;
    }

    .offer-title {
        font-size: 28px;
        font-weight: bold;
        color: #e50914;
        margin-bottom: 16px;
    }

    .offer-description {
        color: #555;
        line-height: 1.7;
        margin-bottom: 24px;
        font-size: 16px;
    }

    .btn-offer {
        display: inline-block;
        background: #e50914;
        color: #fff;
        padding: 14px 32px;
        border-radius: 30px;
        font-weight: bold;
        text-decoration: none;
        transition: .3s;
        box-shadow: 0 4px 15px rgba(229, 9, 20, .3);
    }

    .btn-offer:hover {
        background: #c40812;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(229, 9, 20, .4);
    }

    @media(max-width: 768px) {
        .offers-grid {
            grid-template-columns: 1fr;
        }

        .offers-title {
            font-size: 32px;
        }
    }
    </style>
</x-app-layout>
