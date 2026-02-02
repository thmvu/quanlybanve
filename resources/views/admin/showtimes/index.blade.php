<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Showtimes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold uppercase tracking-wide">Showtime Schedule</h3>
                        <a href="{{ route('admin.showtimes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Add New Showtime
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto flex justify-center">
                        <table class="w-full max-w-6xl leading-normal text-center table-fixed">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-center">Movie</th>
                                    <th class="py-3 px-6 text-center">Cinema / Room</th>
                                    <th class="py-3 px-6 text-center">Start Time</th>
                                    <th class="py-3 px-6 text-center">End Time</th>
                                    <th class="py-3 px-6 text-center w-40">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-200 text-sm font-light">
                                @foreach($showtimes as $showtime)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span class="font-bold text-gray-800">{{ $showtime->movie->title }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <p class="text-gray-900">{{ $showtime->room->cinema->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $showtime->room->name }}</p>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-bold">{{ $showtime->start_time->format('d/m H:i') }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <span class="text-gray-500">{{ $showtime->end_time->format('H:i') }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center align-middle">
                                            <div class="flex item-center justify-center space-x-4">
                                                <a href="{{ route('admin.showtimes.edit', $showtime) }}" class="text-yellow-500 hover:text-yellow-600 transform hover:scale-125 transition" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.showtimes.destroy', $showtime) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this showtime?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 transform hover:scale-125 transition" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <div class="mt-4">
                        {{ $showtimes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
