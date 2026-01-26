<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Movie Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-10 flex flex-col md:flex-row gap-8">
                    <!-- Movie Poster -->
                    <div class="w-full md:w-1/3 flex justify-center md:justify-start">
                        @if($movie->poster)
                            <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="w-80 rounded-lg shadow-2xl object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-80 h-96 bg-gray-300 rounded-lg shadow-2xl flex items-center justify-center text-gray-500">No Image</div>
                        @endif
                    </div>

                    <!-- Movie Info -->
                    <div class="w-full md:w-2/3 text-gray-900 dark:text-gray-100">
                        <h1 class="text-4xl font-extrabold mb-4 border-b pb-4 border-gray-200 dark:border-gray-700 uppercase">{{ $movie->title }}</h1>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <span class="font-bold text-gray-500 uppercase">Duration:</span>
                                <p class="text-lg">{{ $movie->duration }} min</p>
                            </div>
                            <div>
                                <span class="font-bold text-gray-500 uppercase">Release Date:</span>
                                <p class="text-lg">{{ $movie->release_date->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="font-bold text-gray-500 uppercase mb-2">Description</h3>
                            <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300">{{ $movie->description }}</p>
                        </div>

                        @if($movie->trailer_url)
                            <div class="mb-8">
                                <h3 class="font-bold text-gray-500 uppercase mb-2">Trailer</h3>
                                <a href="{{ $movie->trailer_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Watch Trailer</a>
                            </div>
                        @endif

                        <!-- Showtimes -->
                        <div>
                            <h3 class="font-bold text-red-600 uppercase text-2xl mb-4 border-l-4 border-red-600 pl-3">Book Tickets</h3>
                            
                            @if($movie->showtimes->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        // Group showtimes by date and cinema/room
                                        $groupedShowtimes = $movie->showtimes->groupBy(function($item) {
                                            return $item->start_time->format('Y-m-d');
                                        });
                                    @endphp

                                    @foreach($groupedShowtimes as $date => $showtimes)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                            <h4 class="font-bold mb-2">{{ \Carbon\Carbon::parse($date)->format('l, d/m/Y') }}</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($showtimes as $showtime)
                                                    <a href="{{ route('booking.show', $showtime) }}" class="bg-white border border-gray-300 hover:border-red-500 hover:text-red-500 text-gray-800 font-bold py-2 px-4 rounded shadow-sm transition">
                                                        {{ $showtime->start_time->format('H:i') }}
                                                        <span class="block text-xs font-normal text-gray-500">{{ $showtime->room->cinema->name }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">No showtimes available for this movie yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
