@extends('app')

@section('content')
    @foreach($packs as $pack)
        <div class="grid grid-cols-10 gap-4">
             @foreach($pack as $card)
                 <div>
                     <img class="{{ $card->version->variant === 'Foil' ? 'holo' : ''}}" src="{{ $card->version->frontArt }}" />
                 </div>
             @endforeach
        </div>
    @endforeach
@endsection
