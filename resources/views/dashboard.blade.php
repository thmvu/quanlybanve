<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-2xl text-gray-800 
           hover:text-blue-600 hover:scale-105 
           transition-all duration-300 cursor-pointer">
             Cinema Dashboard
        </h1>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4">

            @if(auth()->user()->role === 'admin')

            @php
                $todayOrders = \App\Models\Order::whereDate('created_at', today())->get();
                $todayRevenue = $todayOrders->sum('total_price');
                $todayTickets = \App\Models\Ticket::whereHas('order', function($q) {
                    $q->whereDate('created_at', today());
                })->count();
                $checkedInTickets = \App\Models\Ticket::where('is_checked_in', true)->count();

                $totalOrders = \App\Models\Order::count();
                $totalRevenue = \App\Models\Order::sum('total_price');
                $totalMovies = \App\Models\Movie::count();
                $totalCinemas = \App\Models\Cinema::count();
            @endphp

            <!-- STATISTIC CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
                    <p class="text-gray-500 text-sm">Today's Orders</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $todayOrders->count() }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Today's Revenue</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ number_format($todayRevenue,0,',','.') }} đ
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Tickets Sold Today</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $todayTickets }}</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Checked-in Tickets</p>
                    <p class="text-3xl font-bold text-red-600">{{ $checkedInTickets }}</p>
                </div>

            </div>

            <!-- OVERVIEW -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold mb-4"> Tổng quan hệ thống</h3>

                    <div class="space-y-3 text-gray-700">

                        <div class="flex justify-between">
                            <span>Tổng số đơn hàng</span>
                            <span class="font-bold">{{ $totalOrders }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Tổng doanh thu</span>
                            <span class="font-bold">
                                {{ number_format($totalRevenue,0,',','.') }} đ
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Tổng số phim</span>
                            <span class="font-bold">{{ $totalMovies }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Tổng số rạp</span>
                            <span class="font-bold">{{ $totalCinemas }}</span>
                        </div>

                    </div>
                </div>

                <!-- QUICK ACTION -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold mb-4"> Thao tác nhanh</h3>

                    <div class="grid grid-cols-2 gap-4">

                        <a href="{{ route('admin.orders.index') }}"
                           class="bg-indigo-500 hover:bg-indigo-600 text-black p-3 rounded-lg text-center font-semibold">
                            Orders
                        </a>

                        <a href="{{ route('admin.tickets.index') }}"
                           class="bg-green-500 hover:bg-green-600 text-black p-3 rounded-lg text-center font-semibold">
                            Tickets
                        </a>

                        <a href="{{ route('admin.movies.index') }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-black p-3 rounded-lg text-center font-semibold">
                            Movies
                        </a>

                        <a href="{{ route('admin.showtimes.index') }}"
                           class="bg-red-500 hover:bg-red-600 text-black p-3 rounded-lg text-center font-semibold">
                            Showtimes
                        </a>

                    </div>
                </div>

            </div>

            @else

            <!-- USER DASHBOARD -->

            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-xl font-semibold mb-2">
                    Chào mừng trở lại, {{ auth()->user()->name }}
                </h3>

                <p class="text-gray-600 mb-6">
                    Quản lý đơn đặt vé và vé của bạn
                </p>

                @php
                    $myOrders = \App\Models\Order::where('user_id', auth()->id())
                        ->with('tickets')
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp

                @if($myOrders->count())

                <div class="space-y-4">

                    @foreach($myOrders as $order)

                    <div class="border rounded-lg p-4 hover:shadow">

                        <div class="flex justify-between">

                            <div>
                                <p class="font-semibold">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $order->tickets->count() }} tickets
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold">
                                    {{ number_format($order->total_price,0,',','.') }} đ
                                </p>

                                <span class="px-3 py-1 text-xs rounded-full font-semibold
                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

                @else

                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4">
                        You haven't booked any tickets yet
                    </p>

                    <a href="{{ route('booking.index') }}"
                       class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold">
                         Book Movie Now
                    </a>
                </div>

                @endif

            </div>

            @endif

        </div>
    </div>

</x-app-layout>
