@extends('layouts.minimal')

@section('content')

<h1>@yield('code')</h1>
<h2>@yield('message')</h2>

<p>{{ __('errors.apologies') }}</p>

<p>
    <a href="{{ lp('/contact') }}" class="btn btn-lg btn-primary mr-3"
       onclick="return showSentry()">
        {{ __('errors.tell_us') }}
    </a>
    <a href="{{ lp('/') }}" class="btn btn-lg btn-secondary">
        {{ __('common.return_home') }}
    </a>
</p>

@endsection

@push('scripts')

@if (app()->bound('sentry') && app('sentry')->getLastEventId())

    <script src="https://browser.sentry-cdn.com/5.6.1/bundle.min.js"
            integrity="sha384-pGTFmbQfua2KiaV2+ZLlfowPdd5VMT2xU4zCBcuJr7TVQozMO+I1FmPuVHY3u8KB"
            crossorigin="anonymous"></script>
    <script>
        Sentry.init({ dsn: "{{ config('sentry.dsn') }}" });
        function showSentry() {
            Sentry.showReportDialog({ eventId: "{{ app()->get('sentry')->getLastEventId() }}" });
            return false;
        }
    </script>

@else

    <script>
        function showSentry() {
            return true;
        }
    </script>

@endif

@endpush
