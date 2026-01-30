<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-gradient-to-r from-red-700 via-rose-600 to-pink-700 shadow-2xl transition-all duration-300" style="background: linear-gradient(90deg, #b91c1c 0%, #e11d48 50%, #be185d 100%);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transform hover:scale-110 transition-transform duration-300">
                        <x-application-logo class="block h-9 w-auto fill-current text-white drop-shadow-lg" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:ms-10 sm:flex sm:items-center ">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home') && !request()->has('status')">
                        {{ __('Phim Hot') }}
                    </x-nav-link>
                    <x-nav-link :href="route('home', ['status' => 'now_showing'])" :active="request()->get('status') == 'now_showing'">
                        {{ __('Phim Đang Chiếu') }}
                    </x-nav-link>
                    <x-nav-link :href="route('home', ['status' => 'coming_soon'])" :active="request()->get('status') == 'coming_soon'">
                        {{ __('Phim Sắp Chiếu') }}
                    </x-nav-link>
                    <x-nav-link :href="route('offers')" :active="request()->routeIs('offers')">
                        {{ __('Ưu Đãi') }}
                    </x-nav-link>
                    
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-4 py-2 border border-white/30 text-sm leading-4 font-medium rounded-full text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 hover:shadow-md">
                                        <div>Admin Manage</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.orders.index')">
                                        {{ __('Orders') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.tickets.index')">
                                        {{ __('Tickets') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.movies.index')">
                                        {{ __('Movies') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.cinemas.index')">
                                        {{ __('Cinemas') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.rooms.index')">
                                        {{ __('Rooms') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.showtimes.index')">
                                        {{ __('Showtimes') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-white/30 text-sm leading-4 font-medium rounded-full text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150 hover:shadow-md">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('my-tickets')">
                                {{ __('Vé Của Tôi') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white hover:text-yellow-200 transition duration-150 ease-in-out">Log in</a>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-2 px-5 py-2 text-sm font-medium text-red-600 bg-white hover:bg-yellow-100 rounded-full shadow-lg transition duration-150 ease-in-out transform hover:-translate-y-0.5">Register</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home') && !request()->has('status')">
                {{ __('Phim Hot') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home', ['status' => 'now_showing'])" :active="request()->get('status') == 'now_showing'">
                {{ __('Phim Đang Chiếu') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home', ['status' => 'coming_soon'])" :active="request()->get('status') == 'coming_soon'">
                {{ __('Phim Sắp Chiếu') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('offers')" :active="request()->routeIs('offers')">
                {{ __('Ưu Đãi') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                     @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                     @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
