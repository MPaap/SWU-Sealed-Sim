@extends('app')

@section('content')
    <div class="xl:hidden bg-red-500 p-4 text-center">
        Smaller screen friendly version is coming some day...
    </div>

    @include('navigation')

    <div class="container mx-auto">

        <div class="text-center my-4">
            Sealed Simulator for Star Wars Unlimited to help practice and win your local and PQ Limited events!
        </div>

        @guest
            <div class="text-center my-4 font-normal text-gray-400">
                Login to keep track of all Sealed pools you ever created, save your decklists and share them with your friends.
            </div>
        @endguest

        <div class="grid grid-cols-3 gap-4">
            @foreach($sets as $set)
                <a href="{{ route('sealed', $set->code) }}" class="rounded-xl overflow-hidden opacity-50 hover:opacity-100">
                    <img src="/images/backgrounds/{{ $set->code }}.png" />
                    <div class="text-center">{{ $set->name }}</div>
                </a>
            @endforeach
        </div>

        <div class="text-center my-8">
            <h2 class="text-xl">Pools Generated</h2>

            <div class="flex justify-center">
                <div class="p-4 text-center">All time<br>{{ $generated['all'] }}</div>
                <div class="p-4 text-center">Last 7 Days<br>{{ $generated['recent'] }}</div>
            </div>
        </div>

        @include('footer')
    </div>
@endsection
