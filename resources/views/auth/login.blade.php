@extends('app')

@section('content')
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-gray-700 rounded shadow p-4 text-center">
            Login with

            <div class="flex justify-center mt-4">
                <a href="{{ route('auth.google') }}" class="hover:grayscale-0 grayscale-100 mx-4">
                    <img class="h-15" src="https://cdn.freebiesupply.com/logos/thumbs/2x/google-g-2015-logo.png" />
                </a>

{{--                <a href="{{ route('auth.google') }}" class="hover:grayscale-0 grayscale-100 mx-4">--}}
{{--                    <img class="h-15" src="https://static.vecteezy.com/system/resources/previews/055/331/336/non_2x/circle-discord-icon-logo-symbol-free-png.png" />--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
@endsection
