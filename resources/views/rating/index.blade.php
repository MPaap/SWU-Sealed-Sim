@extends('app')

@section('content')
    @include('navigation')

    <div class="container mx-auto mt-4">
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-400 bg-gray-800 text-sm">
                <thead class="bg-gray-900">
                <tr>
                    <th class="px-4 py-3 font-semibold text-white text-left">Code</th>
                    <th class="px-4 py-3 font-semibold text-white text-left">Name</th>
                    <th class="px-4 py-3 font-semibold text-white text-right">Cards</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-400">
                @foreach($sets as $set)
                    <tr class="hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 w-20">{{ $set->code }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('rating.show', $set->code) }}" class="text-blue-500 hover:text-blue-300 font-medium transition">
                                {{ $set->name }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-right">{{ $set->card_versions_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        @include('footer')
    </div>
@endsection
