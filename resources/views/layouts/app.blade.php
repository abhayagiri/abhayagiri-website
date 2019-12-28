@extends('layouts.html')

@section('body')

<div id="root">

    @include('app.language')

    <header id="header">
        @include('app.logo-and-buttons')
        @include('app.nav-menu')
        @include('app.search')
        @include('app.banner')
        @include('app.breadcrumbs')
    </header>

    <main id="main">
        @include('app.message')
        @yield('main')
    </main>

    <footer id="footer">
        @include('app.footer')
    </footer>

</div>

@endsection
