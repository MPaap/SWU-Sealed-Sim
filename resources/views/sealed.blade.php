@extends('app')

@section('scripts')
    @vite('resources/js/app.js')
@endsection

@section('content')
    <div v-cloak>
        <div class="grid grid-cols-4" v-if="allCards.length > 0">
            <div class="overflow-y-scroll h-screen col-span-3">
                <div class="p-4">
                    <div class="grid grid-cols-6 gap-4">
                        <div v-for="card in leaders"
                             :key="card.normal_version.number"
                             @mouseenter="showTooltip($event, card)"
                             @mouseleave="hideTooltip"
                             @click="selectLeader(card.normal_version.number)"
                             class="cursor-pointer hover:ring-2 ring-green-500/50 rounded-lg overflow-hidden transition-opacity"
                             :class="[
            selectedLeader == card.normal_version.number ? '' : 'opacity-45',
            card.foil ? 'holo' : ''
         ]">
                            <img :src="card.version.frontArt" />
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
                    <div class="flex">
                        <div class="flex flex-1">
                            <div class="p-2 h-[25]">
                                <font-awesome-icon icon="filter" />
                            </div>

                            <div class="flex-1">
                                <div class="flex gap-2 text-center">
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Villainy', 'aspect')"
                                         :class="show.aspect.includes('Villainy') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/villainy.png" />
                                        @{{ countCardsWithAspect('Villainy') }}
                                    </div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Heroism', 'aspect')"
                                         :class="show.aspect.includes('Heroism') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/heroism.png" />
                                        @{{ countCardsWithAspect('Heroism') }}
                                    </div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Vigilance', 'aspect')"
                                         :class="show.aspect.includes('Vigilance') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/vigilance.png" />
                                        @{{ countCardsWithAspect('Vigilance') }}
                                    </div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Command', 'aspect')"
                                         :class="show.aspect.includes('Command') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/command.png" />
                                        @{{ countCardsWithAspect('Command') }}
                                    </div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Aggression', 'aspect')"
                                         :class="show.aspect.includes('Aggression') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/aggression.png" />
                                        @{{ countCardsWithAspect('Aggression') }}
                                    </div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Cunning', 'aspect')"
                                         :class="show.aspect.includes('Cunning') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/cunning.png" />
                                        @{{ countCardsWithAspect('Cunning') }}
                                    </div>

                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Common', 'rarity')"
                                         :class="show.rarity.includes('Common') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/common.png" />
                                        @{{ countCardsWithRarity('Common') }}</div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Uncommon', 'rarity')"
                                         :class="show.rarity.includes('Uncommon') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/uncommon.png" />
                                        @{{ countCardsWithRarity('Uncommon') }}</div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Rare', 'rarity')"
                                         :class="show.rarity.includes('Rare') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/rare.png" />
                                        @{{ countCardsWithRarity('Rare') }}</div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Legendary', 'rarity')"
                                         :class="show.rarity.includes('Legendary') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/legendary.png" />
                                        @{{ countCardsWithRarity('Legendary') }}</div>
                                    <div class="p-2 border-2 border-gray-300 cursor-pointer flex justify-center hover:border-green-700"
                                         @click="toggleShow('Special', 'rarity')"
                                         :class="show.rarity.includes('Special') ? '' : 'opacity-33'">
                                        <img class="h-[25px]" src="/images/icons/special.png" />
                                        @{{ countCardsWithRarity('Special') }}</div>

                                    <div class="p-2 border-2 border-gray-300 flex justify-center hover:border-green-700">
                                        <div>
                                            <font-awesome-icon icon="sort"></font-awesome-icon>
                                        </div>
                                        <select v-model="sort_by" class="bg-gray-900 px-2 w-full cursor-pointer">
                                            <option value="number">Number</option>
                                            <option value="cost">Cost</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex">
                            <a title="Home" href="{{ route('home') }}" class="flex justify-center p-2 border-2 border-gray-300 hover:border-green-700 cursor-pointer"><img src="/images/logos/logo.svg" class="h-[25px] inline" /></a>
                            <a title="New sealed pool" class="ml-2 p-2 border-2 border-gray-300 hover:border-green-700 cursor-pointer" href="{{ url()->current() }}"><font-awesome-icon icon="refresh" /></a>
                            <button title="Share current pool" class="ml-2 p-2 border-2 border-gray-300 hover:border-green-700 cursor-pointer font-normal" @click="shareSeed"><font-awesome-icon icon="share"></font-awesome-icon></button>
                        </div>
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

                    <div class="grid grid-cols-9 gap-2 mt-2">
                        <div v-for="card in sortedOpenCards" :key="card.tmp_id">
                            <div
                                @mouseenter="showTooltip($event, card)"
                                @mouseleave="hideTooltip"
                                @click="card.type === 'Base' ? selectBase(card.version.number) : moveToSelected(card.tmp_id)"
                                :class="[
          'cursor-pointer hover:ring-2 ring-green-500/50 rounded-lg overflow-hidden relative',
          card.foil ? 'holo' : '',
          selectedBase === getExportCode(card.version.number) ? 'ring-2' : ''
        ]"
                            >
                                <img class="rounded-lg overflow-hidden" :src="card.version.frontArt"/>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="isVisible"
                        ref="floating"
                        :style="floatingStyles"
                        class="z-50 pointer-events-none flex flex-col gap-2"
                    >
                        <div v-if="activeCard" class="flex gap-2">
                            <img
                                v-if="activeCard"
                                :src="activeCard.type === 'Leader' ? activeCard.version.backArt : activeCard.version.frontArt"
                                class="w-72 shadow-2xl rounded-xl border-2 border-gray-700"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-4 overflow-y-scroll h-screen">
                <div class="grid grid-cols-5 gap-4">
                    <div>
                        Deck
                        <div>@{{ selectedCards.length }}/30</div>
                    </div>
                    <div class="col-span-3">
                        BASE:
                        <select v-model="selectedBase" class="bg-gray-900 px-2 w-full">
                            <option v-for="card in bases" :value="getExportCode(card.version.number)">
                                @{{ card.version.rarity }}
                                - @{{ card.aspects.map(a => a.name).join(", ") }} -
                                @{{ card.name }}
                                - HP: @{{ card.health }}
                            </option>
                        </select>
                    </div>
                    <button class="px-2 border-gray-300 border-2 text-white rounded cursor-pointer" @click="exportToJson">Export</button>
                </div>
                <div class="mt-4">
                    <div class="flex">
                        <div @click="switchTab('deck')" :class="tab === 'deck' ? '' : 'opacity-33'" class="px-2 border-2 border-white cursor-pointer">Deck</div>
                        <div @click="switchTab('info')" :class="tab === 'info' ? '' : 'opacity-33'" class="ml-2 px-2 border-2 border-white cursor-pointer">Info</div>
                    </div>

                    <div>
                        <div v-if="tab === 'deck'" class="grid grid-cols-3 gap-2 mt-4">
                            <div v-if="sortedSelectedCards.length > 0"
                                 v-for="card in sortedSelectedCards"
                                 @click="moveToOpen(card.tmp_id)"
                                 class="cursor-pointer hover:ring-2 ring-red-500/50 rounded-lg overflow-hidden">
                                <img :class="card.foil ? 'holo' : ''" :src="card.version.frontArt" />
                            </div>
                        </div>

                        <div v-if="tab === 'info'" class="mt-4">
                            <div >
                                <table class="table-auto border-collapse border border-white text-white w-full">
                                    <thead>
                                    <tr>
                                        <th class="border border-white px-2 py-1">Type</th>
                                        <th class="border border-white px-2 py-1">Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="trait in typeCounts" :key="trait.name">
                                        <td class="border border-white px-2 py-1">@{{ trait.name }}</td>
                                        <td class="border border-white px-2 py-1">@{{ trait.count }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4" >
                                <table class="table-auto border-collapse border border-white text-white w-full">
                                    <thead>
                                    <tr>
                                        <th class="border border-white px-2 py-1">Arena</th>
                                        <th class="border border-white px-2 py-1">Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="trait in arenaCounts" :key="trait.name">
                                        <td class="border border-white px-2 py-1">@{{ trait.name }}</td>
                                        <td class="border border-white px-2 py-1">@{{ trait.count }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4" >
                                <h2 class="text-xl font-bold mb-2">Costs</h2>

                                <bar-chart
                                    :values="costCounts" :labels="costLabels"
                                />
                            </div>

                            <div class="mt-4" >
                                <table class="table-auto border-collapse border border-white text-white w-full">
                                    <thead>
                                    <tr>
                                        <th class="border border-white px-2 py-1">Trait</th>
                                        <th class="border border-white px-2 py-1">Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="trait in traitCounts" :key="trait.name">
                                        <td class="border border-white px-2 py-1">@{{ trait.name }}</td>
                                        <td class="border border-white px-2 py-1">@{{ trait.count }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
