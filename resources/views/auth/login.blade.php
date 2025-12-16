@extends('app')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-gray-700 p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-6 text-center">Admin login</h2>

            @if ($errors->any())
                <div class="mb-4 text-red-600 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required autofocus
                           class="w-full border px-3 py-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border px-3 py-2 rounded">
                </div>

                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" name="remember" class="mr-2">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 cursor-pointer">
                    Log in
                </button>

                <div class="text-center mt-4 text-xs font-normal hover:text-green-500">
                    <a href="{{ route('home') }}">Back to home</a>
                </div>
            </form>
        </div>
    </div>
@endsection
