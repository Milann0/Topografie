<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Games Overview
        </h2>
    </x-slot>

    @php
        $currentSort = request('sort');
        $direction = request('direction') === 'asc' ? 'desc' : 'asc';
    @endphp

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <!-- Filters and Search -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <form method="GET" action="{{ route('games.allIndex') }}" class="flex gap-2 flex-1">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by student name"
                                class="px-4 py-2 border rounded-md focus:outline-none focus:ring w-full max-w-xs h-8"
                            />
                            <select name="game_type" class="px-4 py-2 border rounded-md focus:outline-none focus:ring h-8">
                                <option value="">All Game Types</option>
                                <option value="countries" {{ request('game_type') === 'countries' ? 'selected' : '' }}>Countries</option>
                                <option value="capitals" {{ request('game_type') === 'capitals' ? 'selected' : '' }}>Capitals</option>
                            </select>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 h-8"
                            >
                                Search
                            </button>
                            <!-- Export button -->
                            <a href="{{ route('games.exportCsv', request()->all()) }}"
                               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 h-8 whitespace-nowrap"
                            >
                                Export to Excel
                            </a>
                        </form>

                        <!-- Game Statistics -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-700 mb-2">Statistics</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Total Games:</span>
                                    <span class="font-semibold">{{ $games->count() }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Avg Score:</span>
                                    <span class="font-semibold">{{ $games->count() > 0 ? round($games->avg('percentage'), 1) : 0 }}%</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Countries:</span>
                                    <span class="font-semibold">{{ $games->where('game_type', 'countries')->count() }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Capitals:</span>
                                    <span class="font-semibold">{{ $games->where('game_type', 'capitals')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', array_merge(request()->all(), ['sort' => 'score', 'direction' => $currentSort === 'score' ? $direction : 'desc'])) }}" 
                                       class="hover:text-blue-600">
                                        Score
                                        @if($currentSort === 'score')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    Percentage
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', array_merge(request()->all(), ['sort' => 'user', 'direction' => $currentSort === 'user' ? $direction : 'asc'])) }}" 
                                       class="hover:text-blue-600">
                                        User
                                        @if($currentSort === 'user')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', array_merge(request()->all(), ['sort' => 'game_type', 'direction' => $currentSort === 'game_type' ? $direction : 'asc'])) }}" 
                                       class="hover:text-blue-600">
                                        Game Type
                                        @if($currentSort === 'game_type')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', array_merge(request()->all(), ['sort' => 'finished', 'direction' => $currentSort === 'finished' ? $direction : 'desc'])) }}" 
                                       class="hover:text-blue-600">
                                        Finished
                                        @if($currentSort === 'finished')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($games as $game)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $game->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-medium">{{ $game->score }}</span>
                                        <span class="text-gray-500">/ {{ $game->total_questions ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($game->percentage)
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full {{ $game->percentage >= 80 ? 'bg-green-500' : ($game->percentage >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                         style="width: {{ $game->percentage }}%"></div>
                                                </div>
                                                <span class="font-medium">{{ round($game->percentage, 1) }}%</span>
                                            </div>
                                        @else
                                            <span class="text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a href="{{ route('students.show', $game->user->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $game->user->name }} {{ $game->user->lastname }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $game->game_type === 'countries' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $game->game_type === 'countries' ? '🗺️ Countries' : '🏛️ Capitals' }}
                                        </span>
                                    </td>
                                    <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $game->game_type === 'countries' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $game->game_type === 'countries' ? '🗺️ Countries' : '🏛️ Capitals' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm text-gray-900">{{ $game->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $game->created_at->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        <div class="py-8">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No games found</h3>
                                            <p class="mt-1 text-sm text-gray-500">Get started by playing a game or adjust your search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
