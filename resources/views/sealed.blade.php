@extends('app')

@section('content')
    <div class="grid grid-cols-10 gap-4">
         <div v-for="card in leaders">
             <img :src="card.version.frontArt" />
         </div>
    </div>

    <div class="grid grid-cols-10 gap-4 mt-4">
         <div v-for="card in bases">
             <img :src="card.version.frontArt" />
         </div>
    </div>

    <div class="grid grid-cols-10 gap-4 mt-4">
         <div v-for="card in openCards">
             <img :src="card.version.frontArt" />
         </div>
    </div>
@endsection
