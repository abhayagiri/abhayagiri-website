@extends('layouts/html')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/mix/css/app.css') }}">
    <link rel="stylesheet" href="/{{ \App\Util::getStamp()['manifest']['app.css'] ?: null }}">
    <!-- TODO use non-React CSS -->
@endpush

@section('body')

<div id="root">
<div id="main">
<!-- TODO update HTML layout -->

@include('layouts.app_header')

@yield('main')

@include('layouts.app_footer')

</div>
</div>

<script src="{{ mix('/mix/js/manifest.js') }}"></script>
<script src="{{ mix('/mix/js/vendor.js') }}"></script>
<script src="{{ mix('/mix/js/app.js') }}"></script>

@endsection
