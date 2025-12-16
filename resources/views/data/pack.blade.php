@extends('app')

@section('content')
    <div class="container mx-auto">
        <div class="text-center my-4">
            <form action="{{ route('data.pack') }}" method="post">
                @csrf

                <div>
                    <div class="mb-2">Set</div>
                    <select name="set" class="bg-white text-black p-2">
                        @foreach($sets as $set)
                            <option value="{{ $set->id }}">
                                {{ $set->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 mb-2">Cards</div>

                <div class="grid gap-4 grid-cols-2 md:grid-cols-3 md:grid-cols-4 xl:grid-cols-8">
                    @foreach(range(1, 16) as $number)
                        <div>
                            <input class="bg-white p-2 text-black w-full" placeholder="slot #{{ $number }}" name="cards[{{$number}}]" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-green-500 py-2 px-8 cursor-pointer">SAVE</button>
                </div>
            </form>
        </div>

        <div class="my-4">
            ODDS


        </div>

        <div class="my-4">
            PACKS
            @foreach($packs as $pack)
                <div>
                    {{ $pack->set->name }} - {{ $pack->created_at->format('d-m-Y H:i:s') }} -
                    <a href="{{ route('data.pack.delete', $pack) }}">DELETE</a>
                </div>
                <div class="flex">
                    @foreach($pack->countEachRarityInPack() as $rarity => $count)
                        <div class="flex mr-2 border p-2">
                            <img class="h-[25px]" src="/images/icons/{{ \Illuminate\Support\Str::lower($rarity) }}.png" /> {{ $count }}
                        </div>
                    @endforeach

                    @foreach($pack->countEachAspectInPack() as $aspect => $count)
                        <div class="flex mr-2 border p-2">
                            @foreach(explode('-', $aspect) as $name)
                                @if($name)
                                    <img class="h-[25px]" src="/images/icons/{{ \Illuminate\Support\Str::lower($name) }}.png" />
                                @else
                                    N
                                @endif
                            @endforeach
                            {{ $count }}
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-8 gap-2 mb-4">
                    @foreach($pack->versions as $version)
                        <div title="Slot: {{ $version->pivot->slot }} - {{ $version->number }}">
                            <img class="rounded-lg" src="{{ $version->frontArt }}" />
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
</div>
@endsection
