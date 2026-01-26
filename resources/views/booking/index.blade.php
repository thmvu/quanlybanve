<x-app-layout>

<x-slot name="header"></x-slot>

<!-- ===================== SLIDER ===================== -->

<div class="slider-wrapper">

@foreach($movies->take(5) as $index => $movie)

<div class="slide {{ $index == 0 ? 'active' : '' }}">

    <img src="{{ Storage::url($movie->poster) }}" class="slide-img">

    <div class="slide-overlay"></div>

    <div class="slide-content">
        <h1>{{ $movie->title }}</h1>
        <p>{{ Str::limit($movie->description,120) }}</p>

        <a href="{{ route('booking.movie',$movie) }}" class="btn-book">
            Đặt vé ngay
        </a>
    </div>

</div>

@endforeach

<div class="slider-btn prev" onclick="prevSlide()">❮</div>
<div class="slider-btn next" onclick="nextSlide()">❯</div>

</div>


<!-- ===================== MOVIE SECTION ===================== -->

<section class="movie-section">

<h2 class="section-title">MOVIE SELECTION</h2>

<div class="movie-container">

@foreach($movies as $movie)

<div class="movie-card">

<a href="{{ route('booking.movie',$movie) }}">

<div class="poster-box">

    <img src="{{ Storage::url($movie->poster) }}">

    <div class="poster-hover">
        <span>MUA VÉ</span>
    </div>

</div>

<div class="movie-info">

    <h3>{{ $movie->title }}</h3>
    <p>{{ $movie->duration }} phút</p>

</div>

</a>

</div>

@endforeach

</div>

</section>


<!-- ===================== STYLE ===================== -->

<style>

/* RESET */

* {
    box-sizing: border-box;
}

/* ============ SLIDER ============ */

.slider-wrapper {
    width: 100%;
    height: 40vh;
    min-height: 350px;
    position: relative;
    overflow: hidden;
}

.slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: .8s;
}

.slide.active {
    opacity: 1;
    z-index: 2;
}

.slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.45), transparent);
}

.slide-content {
    position: absolute;
    bottom: 40px;
    left: 60px;
    color: #fff;
    max-width: 500px;
}

.slide-content h1 {
    font-size: 38px;
    font-weight: bold;
}

.slide-content p {
    margin: 10px 0;
    line-height: 1.5;
}

.btn-book {
    display: inline-block;
    background: #e50914;
    padding: 12px 28px;
    border-radius: 6px;
    margin-top: 10px;
    font-weight: bold;
    transition: .3s;
}

.btn-book:hover {
    background: #c40812;
}

/* slider buttons */

.slider-btn {
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

.prev { left: 20px; }
.next { right: 20px; }

/* ============ MOVIE SECTION ============ */

.movie-section {
    padding: 60px 40px;
    background: #f5f7fa;
}

.section-title {
    text-align: center;
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 50px;
    color: #111;
}

/* FLEX CONTAINER */

.movie-container {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
}

/* CARD */

.movie-card {
    width: calc(25% - 20px);
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    transition: .3s;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

.movie-card:hover {
    transform: translateY(-8px);
}

/* POSTER */

.poster-box {
    position: relative;
    height: 500px;
}

.poster-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* HOVER */

.poster-hover {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: .3s;
}

.poster-hover span {
    background: #e50914;
    color: #fff;
    padding: 10px 22px;
    border-radius: 20px;
    font-weight: bold;
}

.poster-box:hover .poster-hover {
    opacity: 1;
}

/* INFO */

.movie-info {
    padding: 15px;
}

.movie-info h3 {
    font-size: 18px;
    font-weight: bold;
    color: #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.movie-info p {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

/* RESPONSIVE */

@media(max-width: 1024px) {
    .movie-card { width: calc(33.33% - 20px); }
}

@media(max-width: 768px) {
    .movie-card { width: calc(50% - 20px); }
}

@media(max-width: 480px) {
    .movie-card { width: 100%; }
}

</style>


<!-- ===================== SCRIPT ===================== -->

<script>

let currentSlide = 0;
let slides = document.querySelectorAll('.slide');

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
}

function nextSlide() {
    currentSlide++;
    if(currentSlide >= slides.length) currentSlide = 0;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide--;
    if(currentSlide < 0) currentSlide = slides.length - 1;
    showSlide(currentSlide);
}

setInterval(nextSlide, 5000);

</script>

</x-app-layout>
