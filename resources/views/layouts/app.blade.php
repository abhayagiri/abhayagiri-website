@extends('layouts.html')

@section('body')

<div id="root">

    <header id="header">
        @include('app.header')
    </header>

    <main id="main">
        @include('app.message')
        @yield('main')
        <gallery-manager></gallery-manager>
    </main>

    <footer id="footer">
        @include('app.footer')
    </footer>

</div>

@endsection
