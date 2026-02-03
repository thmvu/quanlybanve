<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold uppercase tracking-wide">Order List</h3>
                        <!-- Search form could go here, omitting for now to match simplicity -->
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto flex justify-center">
                        <table class="w-full max-w-7xl leading-normal text-center table-fixed">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-center w-24">ID</th>
                                    <th class="py-3 px-6 text-center">User</th>
                                    <th class="py-3 px-6 text-center">Total Price</th>
                                    <th class="py-3 px-6 text-center">Payment</th>
                                    <th class="py-3 px-6 text-center w-32">Status</th>
                                    <th class="py-3 px-6 text-center w-40">Date</th>
                                    <th class="py-3 px-6 text-center w-32">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-200 text-sm font-light">
                                @forelse($orders as $order)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-3 px-6 text-center whitespace-nowrap">
                                            <span class="font-medium">#{{ $order->id }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <div class="flex flex-col items-center">
                                                <span class="font-bold">{{ $order->user->name ?? 'Guest' }}</span>
                                                <span class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span class="uppercase text-xs font-semibold tracking-wide">
                                                {{ $order->payment_method }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            @if($order->status === 'paid')
                                                <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs font-bold uppercase">Paid</span>
                                            @elseif($order->status === 'pending')
                                                <span class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs font-bold uppercase">Pending</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs font-bold uppercase">Cancelled</span>
                                            @else
                                                <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs font-bold uppercase">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <div class="flex item-center justify-center">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="transform hover:scale-125 transition text-blue-500 hover:text-blue-600" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
