@extends('layouts/html')

@push('styles')
<style>
    html, body { height: 100%; }
    body { display: flex; align-items: center; background-color: #f06000; }
    main { margin: auto; }
</style>
@endpush

@section('body')

<main role="main">

@yield('content')

</main>

@endsection
