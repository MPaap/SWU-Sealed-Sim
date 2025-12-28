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

        <div class="text-center my-8">
            <h2 class="text-xl">Pools Generated</h2>

            <div class="flex justify-center">
                <div class="p-4 text-center">All time<br>{{ $generated['all'] }}</div>
                <div class="p-4 text-center">Last 7 Days<br>{{ $generated['recent'] }}</div>
            </div>
        </div>

        <div class="text-center my-4">
            Help contribute on <a target="_blank" class="text-blue-500 underline" href="https://github.com/MPaap/SWU-Sealed-Sim">Github</a>.
        </div>

        <div class="text-center my-4 font-normal text-sm opacity-50">
            swusealed.com is in no way affiliated with Disney or Fantasy Flight Games. Star Wars characters, cards, logos, and art are property of Disney and/or Fantasy Flight Games.
        </div>

        <div class="flex justify-center font-normal text-sm opacity-33">
            @auth()
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <a class="mx-2 hover:text-green-500" href="{{ route('data.pack') }}">Add pack data</a>

                    <button type="submit"
                            class="cursor-pointer mx-2 hover:text-red-500">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="hover:text-blue-500">Login</a>
            @endif
        </div>
    </div>
@endsection
