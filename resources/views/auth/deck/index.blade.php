@extends('app')

@section('content')
    @include('navigation')

    <div class="mx-auto container">
        <div class="py-4">
            Saved Decks
        </div>

        <div class="grid grid-cols-4 gap-4">
            @foreach($decks as $deck)
                <a href="{{ route('deck.show', $deck->id) }}">
                    <div class="grid grid-cols-2 gap-2">
                        <img class="rounded-lg" src="{{ $deck->leaderCardVersion->frontArt }}" />
                        <img class="rounded-lg" src="{{ $deck->baseCardVersion->frontArt }}" />
                    </div>
                    <div class="text-center">{{ $deck->set->code }} - {{ $deck->seed }}</div>
                    <div class="text-center text-sm opacity-50">{{ $deck->created_at->ago() }}</div>
                </a>
            @endforeach
        </div>

        {!! $decks->links() !!}
    </div>
@endsection
