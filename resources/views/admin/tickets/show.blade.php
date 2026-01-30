<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ticket Details') }}
            </h2>
            <a href="{{ route('admin.tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to Tickets
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $ticket->code }}</h3>
                        @if($ticket->is_checked_in)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                ✓ Checked In
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending Check-in
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Movie Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Movie</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->showtime->movie->title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cinema</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->showtime->room->cinema->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Room</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->showtime->room->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Seat</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Showtime</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->showtime->start_time->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Customer & Payment</h4>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer Name</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->order->user->name ?? 'Guest' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $ticket->order->user->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Order ID</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('admin.orders.show', $ticket->order) }}" class="text-indigo-600 hover:text-indigo-900">
                                            #{{ $ticket->order->id }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Price</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($ticket->price, 0, ',', '.') }} VND</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($ticket->order->payment_method) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$ticket->is_checked_in)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                            <form method="POST" action="{{ route('admin.tickets.checkIn', $ticket) }}">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                                    ✓ Check In This Ticket
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
