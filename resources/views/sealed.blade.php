@extends('app')

@section('content')
    <div class="grid grid-cols-4" v-if="allCards.length > 0">
        <div class="overflow-y-scroll h-screen col-span-3">
            <div class="p-4">
                <div class="grid grid-cols-6 gap-4">
                    <div v-for="card in leaders"
                         @click="selectLeader(card.version.number)"
                         class=""
                         :class="selectedLeader == card.version.number ? '' : 'opacity-45'">
                        <img class="rounded-lg overflow-hidden" :src="card.version.frontArt" />
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="border-b-2 border-white w-[80%] opacity-60"></div>
            </div>

            {{--    <div class="bg-teal-100 p-4">--}}
            {{--        <div>Bases</div>--}}
            {{--        <div class="grid grid-cols-6 gap-4 mt-4">--}}
            {{--             <div v-for="card in bases">--}}
            {{--                 <img :src="card.version.frontArt" />--}}
            {{--             </div>--}}
            {{--        </div>--}}
            {{--    </div>--}}


            <div class="p-4">
                <div class="grid grid-cols-14 gap-2 text-center">
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Villainy', 'aspect')"
                         :class="show.aspect.includes('Villainy') ? '' : 'opacity-33'">
                        <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                    </div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Heroism', 'aspect')"
                         :class="show.aspect.includes('Heroism') ? '' : 'opacity-33'">
                        <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                    </div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Common', 'rarity')"
                         :class="show.rarity.includes('Common') ? '' : 'opacity-33'">C: @{{ commons }}</div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Special', 'rarity')"
                         :class="show.rarity.includes('Special') ? '' : 'opacity-33'">S: @{{ specials }}</div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Uncommon', 'rarity')"
                         :class="show.rarity.includes('Uncommon') ? '' : 'opacity-33'">U: @{{ uncommons }}</div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Rare', 'rarity')"
                         :class="show.rarity.includes('Rare') ? '' : 'opacity-33'">R: @{{ rares }}</div>
                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center"
                         @click="toggleShow('Legendary', 'rarity')"
                         :class="show.rarity.includes('Legendary') ? '' : 'opacity-33'">L: @{{ legendaries }}</div>

                </div>

                <div class="grid grid-cols-15 gap-2 text-center mt-2">
                    <div class="border-2 border-[#6694ce] p-2 grid grid-cols-3 col-span-3">
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Vigilance_0519102eb9.png" />
                            @{{ getAspect(['Vigilance']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Vigilance', 'Villainy']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Vigilance', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="border-2 border-[#41ad49] p-2 grid grid-cols-3 col-span-3">
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Command_79e2808348.png" />
                            @{{ getAspect(['Command']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Command', 'Villainy']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Command', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="border-2 border-[#d2232a] p-2 grid grid-cols-3 col-span-3">
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Aggression_ceca8f7d7c.png" />
                            @{{ getAspect(['Aggression']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Aggression', 'Villainy']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Aggression', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="border-2 border-[#fdb933] p-2 grid grid-cols-3 col-span-3">
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Cunning_91fedef0ce.png" />
                            @{{ getAspect(['Cunning']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ getAspect(['Cunning', 'Villainy']) }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ getAspect(['Cunning', 'Heroism']) }}
                        </div>
                    </div>

                    <div class="border-2 border-gray-500 p-2 grid grid-cols-3 col-span-3">
                        <div class="flex justify-center">
                            @{{ aspectLess() }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Villainy_3e06e5ffdb.png" />
                            @{{ villainy() }}
                        </div>
                        <div class="flex justify-center">
                            <img class="mr-1 h-[25px]" src="https://cdn.starwarsunlimited.com//medium_SWH_Aspects_Heroism_fd98140fb6.png" />
                            @{{ heroism() }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-2 mt-2">
                    <div v-for="card in sortedOpenCards" @click="moveToSelected(card.tmp_id)" class="cursor-pointer">
                        <img class="rounded-lg overflow-hidden" :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-4 overflow-y-scroll h-screen">
            <div class="flex justify-between">
                <div>Deck @{{ selectedCards.length }}</div>
                <div>
                    BASE:
                    <select v-model="selectedBase" class="bg-gray-900 px-2">
                        <option value="SEC_019">Vigilance</option>
                        <option value="SEC_021">Command</option>
                        <option value="SEC_023">Aggression</option>
                        <option value="SEC_025">Cunning</option>
                    </select>
                </div>
                <button class="px-2 border-gray-300 border-2 text-white rounded cursor-pointer" @click="exportToJson">Export</button>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4">
                <div v-for="card in sortedSelectedCards" @click="moveToOpen(card.tmp_id)" class="cursor-pointer">
                    <img :class="card.version.variant === 'Foil' ? 'holo' : ''" :src="card.version.frontArt" />
                </div>
            </div>
        </div>
    </div>
@endsection
