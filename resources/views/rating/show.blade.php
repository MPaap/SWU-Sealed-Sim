@extends('app')

@section('scripts')
    @vite('resources/js/app.js')
@endsection

@section('content')
    @include('navigation')

    <div class="container mx-auto mt-4" v-cloak>

        @guest
            <div class="text-center my-4 font-normal text-gray-400">
                <a href="{{ route('login') }}" class="underline text-blue-500">Login</a> to rate cards
            </div>
        @endguest

        <div class="grid gap-4 grid-cols-6">
            @foreach($set->cardVersions as $cardVersion)
                <div class="flex flex-col h-full">
                    <div class="flex-grow">
                        <img src="{{ $cardVersion->frontArt }}"
                             loading="lazy"
                             class="rounded-lg w-full"
                             @mouseenter="showTooltip($event, '{{ $cardVersion->backArt ?: $cardVersion->frontArt }}')"
                             @mouseleave="hideTooltip" />
                    </div>

                    <div class="mt-2">
                        <div class="font-medium">
                            {{ $cardVersion->card->name }}
                        </div>

                        <div class="text-sm opacity-60 font-normal">{{ $cardVersion->card->subtitle }}</div>

                        <div class="flex justify-between">
                            <div class="mt-1" title="Community Rating">
                                <font-awesome-icon icon="star"></font-awesome-icon>
                                {{ $cardVersion->card->ratings_avg_rating ? number_format($cardVersion->card->ratings_avg_rating, 1) : 'N/A' }}
                            </div>

                            @auth()
                                <div>
                                    <label for="rate_{{$cardVersion->card->id}}">Rate:</label>
                                    <select @change="addRating($event, {{$cardVersion->card->id}}, '{{$set->code}}')" id="rate_{{$cardVersion->card->id}}">
                                        <option value="">None</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ $cardVersion->card->userRating?->rating == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
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
                    :src="activeCard"
                    class="w-72 shadow-2xl rounded-xl border-2 border-gray-700"
                />
            </div>
        </div>


        @include('footer')
    </div>
@endsection
