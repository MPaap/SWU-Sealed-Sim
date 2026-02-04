<div class="bg-gray-800">
    <div class="mx-auto container">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="p-4">
                    <img class="h-8" src="/images/logos/logo.svg" />
                </a>

                <a href="{{ route('auth.pool') }}" class="p-4 hover:bg-gray-700">
                    Pool history
                </a>

                <a href="{{ route('auth.deck') }}" class="p-4 hover:bg-gray-700">
                    Saved Decks
                </a>

                <a href="{{ route('rating.index') }}" class="p-4 hover:bg-gray-700">
                    Ratings
                </a>
            </div>

            <div class="flex items-center">
                @auth()
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf

                        <button type="submit"
                                class="cursor-pointer p-4 hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="p-4 hover:bg-gray-700">Login</a>
                @endif
            </div>
        </div>
    </div>
</div>
