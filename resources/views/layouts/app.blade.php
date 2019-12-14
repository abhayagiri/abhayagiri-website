@extends('layouts.html')

@section('body')

<div id="root">

    @include('page.language')

    <div id="header">

        @include('page.logo-and-buttons')
        @include('page.nav-menu')
        @include('page.search')
        @include('page.banner')
        @include('page.breadcrumbs')

    </div>

    @yield('main')

    <hr>

    @include('page.footer')

</div>

@endsection
