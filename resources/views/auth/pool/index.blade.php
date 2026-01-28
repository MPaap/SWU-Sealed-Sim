@extends('app')

@section('content')
    @include('navigation')

    <div class="mx-auto container">
        <div class="py-4">
            Sealed Pools History
        </div>

        <div class="grid grid-cols-6 gap-4">
            @foreach($logs as $log)
                <a href="{{ route('sealed', ['set' => $log->set->code, 'seed' => $log->seed]) }}">
                    <img src="/images/backgrounds/{{ $log->set->code }}.png" />
                    <div class="text-center">{{ $log->seed }}</div>
                    <div class="text-center text-sm opacity-50">{{ $log->created_at->ago() }}</div>
                </a>
            @endforeach
        </div>

        {!! $logs->links() !!}
    </div>
@endsection
