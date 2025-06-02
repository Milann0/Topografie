<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Games
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
                    <form method="GET" action="{{ route('games.allIndex') }}" class="mb-4 flex gap-2">
                        <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by student name"
                                class="px-4 py-2 border rounded-md focus:outline-none focus:ring w-full max-w-xs"
                        />
                        <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Search
                        </button>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', ['sort' => 'score', 'direction' => $currentSort === 'score' ? $direction : 'asc']) }}">
                                        Score
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', ['sort' => 'user', 'direction' => $currentSort === 'user' ? $direction : 'asc']) }}">
                                        User
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    <a href="{{ route('games.allIndex', ['sort' => 'finished', 'direction' => $currentSort === 'finished' ? $direction : 'asc']) }}">
                                        Finished
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($games as $game)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $game->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $game->score }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a href="{{ route('students.show', $game->user->id) }}">{{ $game->user->name }} {{ $game->user->lastname }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $game->created_at }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm text-gray-500">
                                        No games found.
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
