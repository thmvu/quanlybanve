<x-app-layout>
    <x-slot name="header"></x-slot>

    <!-- Movie Details Section -->
    <section class="movie-details-section">
        <div class="details-container">
            <!-- Poster -->
            <div class="poster-container">
                @if($movie->poster)
                    <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="movie-poster">
                @else
                    <div class="no-poster">No Image</div>
                @endif
            </div>

            <!-- Info -->
            <div class="info-container">
                <h1 class="movie-title">{{ $movie->title }}</h1>
                
                <div class="movie-meta">
                    <div class="meta-item">
                        <span class="meta-label">Thời lượng:</span>
                        <span class="meta-value">{{ $movie->duration }} phút</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Ngày phát hành:</span>
                        <span class="meta-value">{{ $movie->release_date->format('d/m/Y') }}</span>
                    </div>
                    @if($movie->age_rating)
                    <div class="meta-item">
                        <span class="meta-label">Độ tuổi:</span>
                        <span class="meta-value">{{ $movie->age_rating }}</span>
                    </div>
                    @endif
                </div>

                <div class="movie-description">
                    <h3 class="section-heading">Nội dung phim</h3>
                    <p>{{ $movie->description }}</p>
                </div>

                @if($movie->trailer_url)
                <div class="trailer-section">
                    <a href="{{ $movie->trailer_url }}" target="_blank" class="btn-trailer">
                        ▶ Xem Trailer
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Showtimes -->
        <div class="showtimes-wrapper">
            <h2 class="showtimes-title">LỊCH CHIẾU & ĐẶT VÉ</h2>
            
            @if($movie->showtimes->count() > 0)
                <div class="showtimes-grid">
                    @php
                        $groupedShowtimes = $movie->showtimes->groupBy(function($item) {
                            return $item->start_time->format('Y-m-d');
                        });
                    @endphp

                    @foreach($groupedShowtimes as $date => $showtimes)
                        <div class="showtime-card">
                            <h4 class="showtime-date">{{ \Carbon\Carbon::parse($date)->format('l, d/m/Y') }}</h4>
                            <div class="time-slots">
                                @foreach($showtimes as $showtime)
                                    <a href="{{ route('booking.show', $showtime) }}" class="time-slot">
                                        <span class="time">{{ $showtime->start_time->format('H:i') }}</span>
                                        <span class="cinema">{{ $showtime->room->cinema->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-showtimes">Chưa có lịch chiếu cho phim này.</p>
            @endif
        </div>
    </section>

    <style>
    .movie-details-section {
        padding: 60px 40px;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .details-container {
        max-width: 1200px;
        margin: 0 auto 60px;
        display: flex;
        gap: 40px;
        background: #fff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,.1);
    }

    .poster-container {
        flex-shrink: 0;
    }

    .movie-poster {
        width: 100%;
        max-width: 400px;
        max-height: 600px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,.15);
        object-fit: cover;
    }

    .no-poster {
        width: 400px;
        height: 600px;
        background: #ddd;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 18px;
    }

    .info-container {
        flex: 1;
    }

    .movie-title {
        font-size: 42px;
        font-weight: bold;
        color: #111;
        margin-bottom: 20px;
        text-transform: uppercase;
        border-bottom: 4px solid #e50914;
        padding-bottom: 12px;
    }

    .movie-meta {
        display: flex;
        gap: 30px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 18px;
        color: #333;
        font-weight: 600;
    }

    .movie-description {
        margin-bottom: 30px;
    }

    .section-heading {
        font-size: 20px;
        font-weight: bold;
        color: #e50914;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .movie-description p {
        line-height: 1.8;
        color: #555;
        font-size: 16px;
    }

    .trailer-section {
        margin-top: 20px;
    }

    .btn-trailer {
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

    .btn-trailer:hover {
        background: #c40812;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(229, 9, 20, .4);
    }

    .showtimes-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }

    .showtimes-title {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        color: #111;
        margin-bottom: 40px;
    }

    .showtimes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .showtime-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,.08);
        transition: .3s;
    }

    .showtime-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 25px rgba(0,0,0,.12);
    }

    .showtime-date {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 16px;
        border-bottom: 2px solid #e50914;
        padding-bottom: 8px;
    }

    .time-slots {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .time-slot {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #f5f7fa;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 12px 16px;
        text-decoration: none;
        transition: .3s;
        min-width: 100px;
    }

    .time-slot:hover {
        border-color: #e50914;
        background: #fff;
        transform: scale(1.05);
    }

    .time {
        font-size: 18px;
        font-weight: bold;
        color: #111;
    }

    .cinema {
        font-size: 11px;
        color: #666;
        margin-top: 4px;
        text-align: center;
    }

    .no-showtimes {
        text-align: center;
        color: #999;
        font-size: 18px;
        padding: 40px;
    }

    /* Responsive */
    @media(max-width: 768px) {
        .details-container {
            flex-direction: column;
            padding: 24px;
        }

        .movie-poster {
            max-width: 100%;
            max-height: 500px;
        }

        .movie-title {
            font-size: 28px;
        }

        .movie-meta {
            gap: 20px;
        }
    }
    </style>
</x-app-layout>
