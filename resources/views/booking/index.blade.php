<x-app-layout>

<x-slot name="header"></x-slot>

<!-- ===================== SLIDER ===================== -->

<div class="khung-trangchu">

@foreach($movies->take(5) as $index => $movie)

<div class="slide-phim {{ $index == 0 ? 'dang-hien' : '' }}">

    <img src="{{ Storage::url($movie->poster) }}" class="anh-slide">

    <div class="lop-phu"></div>

    <div class="noidung-slide">
        <h1>{{ $movie->title }}</h1>
        <p>{{ Str::limit($movie->description,120) }}</p>

        <a href="{{ route('booking.movie',$movie) }}" class="nut-datve">
            Đặt vé ngay
        </a>
    </div>

</div>

@endforeach

<div class="nut-chuyen truoc" onclick="slidePhimTruoc()">❮</div>
<div class="nut-chuyen sau" onclick="slidePhimSau()">❯</div>

</div>


<!-- ===================== PHẦN PHIM ===================== -->

<section class="phan-phim">

    <div class="khung-timkiem">
        <form action="{{ route('booking.index') }}" method="GET">
            <input type="text" name="search" placeholder="Bạn muốn xem phim gì hôm nay?" value="{{ request('search') }}">
            <button type="submit">TÌM KIẾM</button>
        </form>
    </div>

<h2 class="tieude-phan">MOVIE SELECTION</h2>

<div class="luoi-phim">

@foreach($movies as $movie)

<div class="the-phim">

<a href="{{ route('booking.movie',$movie) }}">

<div class="hop-poster">

    <img src="{{ Storage::url($movie->poster) }}">

    <div class="lop-hover">
        <span>MUA VÉ</span>
    </div>

</div>

<div class="thongtin-phim">

    <h3>{{ $movie->title }}</h3>
    <p>{{ $movie->duration }} phút</p>

</div>

</a>

</div>

@endforeach

</div>

<!-- Phân trang -->
<div class="khung-phantrang">
    {{ $movies->links() }}
</div>

</section>


<!-- CSS -->

<style>

/* RESET */

* {
    box-sizing: border-box;
}



.khung-trangchu {
    width: 100%;
    height: 40vh;
    min-height: 350px;
    position: relative;
    overflow: hidden;
}

.slide-phim {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: .8s;
}

.slide-phim.dang-hien {
    opacity: 1;
    z-index: 2;
}

.anh-slide {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lop-phu {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.45), transparent);
}

.noidung-slide {
    position: absolute;
    bottom: 40px;
    left: 60px;
    color: #fff;
    max-width: 500px;
}

.noidung-slide h1 {
    font-size: 38px;
    font-weight: bold;
}

.noidung-slide p {
    margin: 10px 0;
    line-height: 1.5;
}

.nut-datve {
    display: inline-block;
    background: #e50914;
    padding: 12px 28px;
    border-radius: 6px;
    margin-top: 10px;
    font-weight: bold;
    transition: .3s;
}

.nut-datve:hover {
    background: #c40812;
}

/* Nút chuyển slide */

.nut-chuyen {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,.7);
    color: #000;
    font-size: 26px;
    padding: 8px 14px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
}

.truoc { left: 20px; }
.sau { right: 20px; }



.phan-phim {
    padding: 60px 40px;
    background: #f5f7fa;
}

.tieude-phan {
    text-align: center;
    font-size: 36px;
    font-weight: bold;
    color: #111;
}

/* Ô tìm kiếm */

.khung-timkiem {
    max-width: 600px;
    margin: 0 auto 40px;
    text-align: center;
}

.khung-timkiem form {
    display: flex;
    gap: 10px;
}

.khung-timkiem input {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #ddd;
    border-radius: 30px;
    font-size: 16px;
    outline: none;
    transition: .3s;
}

.khung-timkiem input:focus {
    border-color: #e50914;
}

.khung-timkiem button {
    padding: 12px 30px;
    background: #e50914;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    transition: .3s;
}

.khung-timkiem button:hover {
    background: #c40812;
}

/* Lưới phim */

.luoi-phim {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
}

/* Thẻ phim */

.the-phim {
    width: calc(25% - 20px);
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    transition: .3s;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

.the-phim:hover {
    transform: translateY(-8px);
}

/* Poster */

.hop-poster {
    position: relative;
    height: 500px;
}

.hop-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Hover */

.lop-hover {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: .3s;
}

.lop-hover span {
    background: #e50914;
    color: #fff;
    padding: 10px 22px;
    border-radius: 20px;
    font-weight: bold;
}

.hop-poster:hover .lop-hover {
    opacity: 1;
}

/* Thông tin phim */

.thongtin-phim {
    padding: 15px;
}

.thongtin-phim h3 {
    font-size: 18px;
    font-weight: bold;
    color: #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.thongtin-phim p {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

/* Responsive */

@media(max-width: 1024px) {
    .the-phim { width: calc(33.33% - 20px); }
}

@media(max-width: 768px) {
    .the-phim { width: calc(50% - 20px); }
}

@media(max-width: 480px) {
    .the-phim { width: 100%; }
}

/* Phân trang */

.khung-phantrang {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

</style>


<!-- ===================== JAVASCRIPT ===================== -->

<script>

let slideHienTai = 0;
let cacSlide = document.querySelectorAll('.slide-phim');

function hienSlide(viTri) {
    cacSlide.forEach(slide => slide.classList.remove('dang-hien'));
    cacSlide[viTri].classList.add('dang-hien');
}

function slidePhimSau() {
    slideHienTai++;
    if(slideHienTai >= cacSlide.length) slideHienTai = 0;
    hienSlide(slideHienTai);
}

function slidePhimTruoc() {
    slideHienTai--;
    if(slideHienTai < 0) slideHienTai = cacSlide.length - 1;
    hienSlide(slideHienTai);
}

setInterval(slidePhimSau, 5000);

</script>

</x-app-layout>
