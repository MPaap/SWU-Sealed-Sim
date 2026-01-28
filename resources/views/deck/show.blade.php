@extends('app')

@section('scripts')
    @vite('resources/js/app.js')
@endsection

@section('content')
    @include('navigation')

    <div class="mx-auto container">
        <div class="py-4">
            {{ $deck->set->name }} - <a title="Build a deck with seed" class="underline text-blue-500" href="{{ route('sealed', ['set' => $deck->set->code, 'seed' => $deck->seed]) }}">{{ $deck->seed }}</a> - {{ $deck->created_at->ago() }}
        </div>

        <div class="grid grid-cols-6 gap-4">
            <img class="rounded-lg" src="{{ $deck->leaderCardVersion->frontArt }}" />
            <img class="rounded-lg" src="{{ $deck->baseCardVersion->frontArt }}" />
        </div>

        <div class="mt-8 mb-4">
            Decklist
            <button @click="copyBySelector('#json')"
                    class="cursor-pointer px-1 border-2 border-white rounded ml-2"
                    title="Copy Decklist JSON"
                    @click="copyDecklist"><font-awesome-icon icon="file-arrow-down" /></button>
        </div>

        <div class="grid grid-cols-10 gap-4">
            @foreach($deck->cardVersions as $cardVersion)
                <div>
                    <img class="rounded-lg" src="{{ $cardVersion->frontArt }}">
                </div>
            @endforeach
        </div>

        <div class="hidden">
            <textarea id="json">{{ json_encode($json) }}</textarea>
        </div>

        @include('footer')
    </div>
@endsection
