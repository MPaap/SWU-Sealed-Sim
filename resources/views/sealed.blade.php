@extends('app')

@section('content')
    <div class="grid grid-cols-4 gap-4" v-if="allCards.length > 0">
        <div class="overflow-y-scroll h-screen col-span-3">
            <div class="bg-red-100 p-4">
                <div>Leaders</div>
                <div class="grid grid-cols-6 gap-4 mt-4">
                    <div v-for="card in leaders">
                        <img :src="card.version.frontArt" />
                    </div>
                </div>
            </div>

            {{--    <div class="bg-teal-100 p-4">--}}
            {{--        <div>Bases</div>--}}
            {{--        <div class="grid grid-cols-6 gap-4 mt-4">--}}
            {{--             <div v-for="card in bases">--}}
            {{--                 <img :src="card.version.frontArt" />--}}
            {{--             </div>--}}
            {{--        </div>--}}
            {{--    </div>--}}


            <div class="bg-orange-100 p-4">
                <div>Card pool @{{ openCards.length }}</div>
                <div class="flex justify-between">
                    <div @click="toggleShow('Common')" :class="show.includes('Common') ? '' : 'text-red-500'">C: @{{ commons }}</div>
                    <div @click="toggleShow('Special')" :class="show.includes('Special') ? '' : 'text-red-500'">S: @{{ specials }}</div>
                    <div @click="toggleShow('Uncommon')" :class="show.includes('Uncommon') ? '' : 'text-red-500'">U: @{{ uncommons }}</div>
                    <div @click="toggleShow('Rare')" :class="show.includes('Rare') ? '' : 'text-red-500'">R: @{{ rares }}</div>
                    <div @click="toggleShow('Legendary')" :class="show.includes('Legendary') ? '' : 'text-red-500'">L: @{{ legendaries }}</div>
                </div>

                <div class="flex justify-between">
                    <div class="bg-[#6694ce] p-4 flex">
                        <div class="flex">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Vigilance_0519102eb9.png" />
                            @{{ getAspect(['Vigilance']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Vigilance', 'Villainy']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Vigilance', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="bg-[#41ad49] p-4 flex">
                        <div class="flex">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Command_79e2808348.png" />
                            @{{ getAspect(['Command']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Command', 'Villainy']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Command', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="bg-[#d2232a] p-4 flex">
                        <div class="flex">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Aggression_ceca8f7d7c.png" />
                            @{{ getAspect(['Aggression']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Aggression', 'Villainy']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Aggression', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="bg-[#fdb933] p-4 flex">
                        <div class="flex">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Cunning_91fedef0ce.png" />
                            @{{ getAspect(['Cunning']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Cunning', 'Villainy']) }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Cunning', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="bg-gray-500 p-4 flex">
                        <div class="flex">
                            @{{ aspectLess() }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ villainy() }}
                        </div>
                        <div class="flex ml-4">
                            <img width="25" class="mr-2" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ heroism() }}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-6 gap-4 mt-4">
                    <div v-for="(card, key) in sortedOpenCards" @click="moveToSelected(key)" class="cursor-pointer">
                        <img :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-green-500 p-4 overflow-y-scroll h-screen">
            <div class="flex justify-between">
                <div>Deck @{{ selectedCards.length }}</div>
                <button class="px-2 bg-purple-500 text-white rounded cursor-pointer hover:bg-purple-600" @click="exportToJson">Export</button>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4">
                <div v-for="(card, key) in sortedSelectedCards" @click="moveToOpen(key)" class="cursor-pointer">
                    <img :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                </div>
            </div>
        </div>
    </div>
@endsection
