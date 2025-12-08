@extends('app')

@section('content')
    <div class="xl:hidden bg-red-500 p-4 text-center">
        Smaller screen friendly version is coming some day...
    </div>

    <div class="container mx-auto">
        <div class="text-center my-4">
            Sealed Simulator for Star Wars Unlimited to help practice and win your local and PQ Limited events!
        </div>

        <div class="grid grid-cols-3 gap-4">
            @foreach($sets as $set)
                <a href="{{ route('sealed', $set->code) }}" class="rounded-xl overflow-hidden opacity-50 hover:opacity-100">
                    <img src="/images/backgrounds/{{ $set->code }}.png" />
                    <div class="text-center">{{ $set->name }}</div>
                </a>
            @endforeach
        </div>

        <div class="text-center my-4">
            Help contribute on <a target="_blank" class="text-blue-500 underline" href="https://github.com/MPaap/SWU-Sealed-Sim">Github</a>.
        </div>

        <div class="text-center my-4 font-normal text-sm opacity-50">
            swusealed.com is in no way affiliated with Disney or Fantasy Flight Games. Star Wars characters, cards, logos, and art are property of Disney and/or Fantasy Flight Games.
        </div>
    </div>
@endsection
