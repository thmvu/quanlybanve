<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vé Của Tôi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Danh Sách Vé Đã Đặt</h3>

                    @forelse($orders as $order)
                        <div x-data="{ open: false }" class="mb-3 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition">
                            
                            <!-- Compact Order Summary (Always Visible) -->
                            <div @click="open = !open" class="cursor-pointer bg-gray-50 dark:bg-gray-700 px-4 py-3 flex justify-between items-center hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                                            Đơn #{{ $order->id }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y H:i') }} • {{ $order->tickets->count() }} vé
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($order->total_price, 0, ',', '.') }}đ
                                        </p>
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $order->status === 'paid' ? 'Đã thanh toán' : ($order->status === 'cancelled' ? 'Đã hủy' : ucfirst($order->status)) }}
                                        </span>
                                    </div>
                                    
                                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Detailed Ticket Info (Expandable) -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="p-4 border-t border-gray-200 dark:border-gray-600">
                                
                                @foreach($order->tickets as $ticket)
                                    <div class="flex gap-4 mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                                        <!-- Movie Poster -->
                                        @if($ticket->showtime->movie->poster)
                                            <img src="{{ Storage::url($ticket->showtime->movie->poster) }}" 
                                                 class="w-20 h-28 object-cover rounded-lg shadow-md flex-shrink-0"
                                                 alt="{{ $ticket->showtime->movie->title }}">
                                        @endif

                                        <!-- Ticket Details -->
                                        <div class="flex-1">
                                            <h4 class="font-bold text-base text-gray-900 dark:text-gray-100 mb-2">
                                                {{ $ticket->showtime->movie->title }}
                                            </h4>
                                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                                                <div><strong>Mã vé:</strong> {{ $ticket->code }}</div>
                                                <div><strong>Ghế:</strong> {{ $ticket->seat->row }}{{ $ticket->seat->number }}</div>
                                                <div><strong>Rạp:</strong> {{ $ticket->showtime->room->cinema->name }}</div>
                                                <div><strong>Phòng:</strong> {{ $ticket->showtime->room->name }}</div>
                                                <div class="col-span-2"><strong>Suất chiếu:</strong> {{ $ticket->showtime->start_time->format('H:i, d/m/Y') }}</div>
                                            </div>

                                            <!-- Check-in Status -->
                                            <div class="mt-2">
                                                @if($ticket->is_checked_in)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                                        ✓ Đã Check-in
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">
                                                        Chưa Check-in
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Cancel Button -->
                                @php
                                    $minutesSinceOrder = $order->created_at->diffInMinutes(now());
                                    $canCancel = $minutesSinceOrder <= 5 && $order->status !== 'cancelled';
                                @endphp

                                @if($canCancel)
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <form method="POST" action="{{ route('my-tickets.cancel', $order) }}" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                                Hủy Đơn Hàng (Còn {{ 5 - $minutesSinceOrder }} phút)
                                            </button>
                                        </form>
                                    </div>
                                @elseif($order->status !== 'cancelled' && $minutesSinceOrder > 5)
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ⏱ Đã quá thời gian hủy vé (5 phút sau khi đặt)
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có vé nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bắt đầu đặt vé để xem phim yêu thích!</p>
                            <div class="mt-6">
                                <a href="{{ route('booking.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                                    Đặt Vé Ngay
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
