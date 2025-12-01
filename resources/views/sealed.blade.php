@extends('app')

@section('content')
    <div class="fixed bg-green-500 w-full p-4 grid grid-cols-10 gap-4 shadow">
        <div>Decksize: @{{ selectedCards.length }}</div>
        <div>C: @{{ commons }}</div>
        <div>S: @{{ specials }}</div>
        <div>U: @{{ uncommons }}</div>
        <div>R: @{{ rares }}</div>
        <div>L: @{{ legendaries }}</div>
    </div>

    <div class="p-4">&nbsp;</div>

    <div class="bg-red-100 p-4">
        <div>Leaders</div>
        <div class="grid grid-cols-6 gap-4 mt-4">
             <div v-for="card in leaders">
                 <img :src="card.version.frontArt" />
             </div>
        </div>
    </div>

    <div class="bg-teal-100 p-4">
        <div>Bases</div>
        <div class="grid grid-cols-6 gap-4 mt-4">
             <div v-for="card in bases">
                 <img :src="card.version.frontArt" />
             </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-orange-100 p-4">
            <div>Card pool</div>
            <div class="grid grid-cols-5 gap-4 mt-4">
                <div v-for="(card, key) in sortedOpenCards" @click="moveToSelected(key)" class="cursor-pointer">
                    <img :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                </div>
            </div>
        </div>

        <div class="bg-green-500 p-4">
            <div>Deck</div>
            <div class="grid grid-cols-5 gap-4 mt-4">
                <div v-for="(card, key) in sortedSelectedCards" @click="moveToOpen(key)" class="cursor-pointer">
                    <img :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                </div>
            </div>
        </div>
    </div>
@endsection
