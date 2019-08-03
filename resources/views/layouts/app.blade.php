@extends('layouts/html')

@push('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <!-- TODO update vendor'd CSS -->
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

@endsection
