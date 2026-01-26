<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'admin')
                <!-- Admin Dashboard -->
                @php
                    $todayOrders = \App\Models\Order::whereDate('created_at', today())->get();
                    $todayRevenue = $todayOrders->sum('total_price');
                    $todayTickets = \App\Models\Ticket::whereHas('order', function($q) {
                        $q->whereDate('created_at', today());
                    })->count();
                    $checkedInTickets = \App\Models\Ticket::where('is_checked_in', true)->count();
                    $totalOrders = \App\Models\Order::count();
                    $totalRevenue = \App\Models\Order::sum('total_price');
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Today's Orders -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Orders</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $todayOrders->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Revenue -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Revenue</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($todayRevenue / 1000, 0) }}K</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Tickets Sold -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Tickets</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $todayTickets }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checked In Tickets -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Checked In</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $checkedInTickets }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overall Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Overall Statistics</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalOrders }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Revenue</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalRevenue, 0, ',', '.') }} VND</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Movies</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ \App\Models\Movie::count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Cinemas</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ \App\Models\Cinema::count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.orders.index') }}" class="block w-full text-center bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Orders
                                </a>
                                <a href="{{ route('admin.tickets.index') }}" class="block w-full text-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Tickets
                                </a>
                                <a href="{{ route('admin.movies.index') }}" class="block w-full text-center bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Movies
                                </a>
                                <a href="{{ route('admin.showtimes.index') }}" class="block w-full text-center bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Showtimes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Regular User Dashboard -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Welcome, {{ auth()->user()->name }}!</h3>
                        <p class="mb-4">{{ __("You're logged in!") }}</p>
                        
                        @php
                            $myOrders = \App\Models\Order::where('user_id', auth()->id())->with('tickets')->latest()->take(5)->get();
                        @endphp

                        @if($myOrders->count() > 0)
                            <h4 class="font-semibold mb-3">My Recent Orders</h4>
                            <div class="space-y-3">
                                @foreach($myOrders as $order)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold">Order #{{ $order->id }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->tickets->count() }} ticket(s)</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold">{{ number_format($order->total_price, 0, ',', '.') }} VND</p>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'paid') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">You haven't made any bookings yet.</p>
                            <a href="{{ route('booking.index') }}" class="inline-block mt-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Browse Movies
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
