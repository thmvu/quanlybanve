<x-app-layout>
    <x-slot name="header"></x-slot>

    <div 
        x-data="{ activeSlide: 0, slides: {{ $movies->count() }}, timer: null }"
        x-init="timer = setInterval(() => { activeSlide = (activeSlide + 1) % slides }, 5000)"
        class="relative w-full h-[380px] md:h-[520px] lg:h-[580px] overflow-hidden bg-black group">

        @foreach($movies as $index => $movie)
        <div x-show="activeSlide === {{ $index }}" 
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0">

            <div class="absolute inset-0 bg-cover bg-center scale-110 blur-md opacity-50"
                style="background-image:url('{{ Storage::url($movie->poster) }}')"></div>

            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

            <div class="relative z-10 h-full flex items-center max-w-7xl mx-auto px-6 lg:px-10 gap-10">
                <div class="hidden md:block w-[240px] h-[360px] lg:w-[280px] lg:h-[420px] shadow-2xl border border-white/20 overflow-hidden rounded-lg">
                    <img src="{{ Storage::url($movie->poster) }}" class="w-full h-full object-cover">
                </div>

                <div class="text-white max-w-xl">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold uppercase leading-tight mb-3">
                        {{ $movie->title }}
                    </h2>
                    <p class="text-gray-300 line-clamp-3 mb-6">
                        {{ $movie->description }}
                    </p>
                    <a href="{{ route('booking.movie',$movie) }}"
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 px-7 py-3 font-bold rounded-full tracking-wider transition hover:scale-105 shadow-lg">
                        🎟 Mua Vé Ngay
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <button @click="activeSlide = activeSlide === 0 ? slides-1 : activeSlide-1"
            class="absolute left-5 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black p-3 rounded-full text-white opacity-0 group-hover:opacity-100 transition z-20">
            ❮
        </button>

        <button @click="activeSlide = (activeSlide+1)%slides"
            class="absolute right-5 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black p-3 rounded-full text-white opacity-0 group-hover:opacity-100 transition z-20">
            ❯
        </button>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
            @foreach($movies as $index=>$movie)
            <button 
              @click="activeSlide={{$index}}"
              :class="activeSlide === {{$index}} ? 'bg-red-600 w-8' : 'bg-white/40 w-2'"
              class="h-2 rounded-full transition-all"></button>
            @endforeach
        </div>
    </div>

    <div class="bg-[#fdfcf0] py-12 border-t-8 border-black">

        <div class="flex justify-center items-center mb-10">
            <div class="h-1 bg-black w-16 md:w-24"></div>
            <h2 class="mx-4 text-2xl md:text-3xl font-extrabold tracking-widest uppercase text-gray-900">
                Movie Selection
            </h2>
            <div class="h-1 bg-black w-16 md:w-24"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 justify-center">

                @foreach($movies as $movie)
                <div class="group w-full flex flex-col">

                    <div class="relative w-full overflow-hidden rounded-lg shadow-lg bg-gray-900" style="aspect-ratio: 2/3;">
                        
                        <a href="{{ route('booking.movie',$movie) }}" class="block w-full h-full">
                            @if($movie->poster)
                                <img src="{{ Storage::url($movie->poster) }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 group-hover:opacity-90"
                                    alt="{{ $movie->title }}">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 bg-gray-800 text-xs text-center p-2">
                                    <span>No Image</span>
                                </div>
                            @endif
                        </a>

                        <div class="absolute top-2 left-2 pointer-events-none">
                            <span class="bg-green-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                                P
                            </span>
                        </div>

                        <a href="{{ route('booking.movie',$movie) }}" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-5 rounded-full shadow-lg border border-red-500 uppercase tracking-wide">
                                    Mua Vé
                                </button>
                            </span>
                        </a>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('booking.movie',$movie) }}" 
                           class="block text-base md:text-lg font-bold text-gray-800 dark:text-white uppercase truncate hover:text-red-600 transition-colors duration-200" 
                           title="{{ $movie->title }}">
                            {{ $movie->title }}
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-app-layout>