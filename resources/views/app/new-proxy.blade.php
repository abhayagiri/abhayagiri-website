@extends('layouts.app')

@php
$manifest = \App\Util::getStamp()['manifest'] ?? null;
@endphp

@section('title', app('pages')->current()->title)

@push('styles')
    @if ($manifest)
        <link rel="stylesheet" href="/{{ $manifest['app.css'] }}">
    @else
        <script src="http://localhost:9000/new/bundle.css"></script>
    @endif
@endpush

@push('scripts')
    @if ($manifest)
        <script src="/{{ $manifest['app.js']}}"></script>
    @else
        <script src="http://localhost:9000/new/bundle.js"></script>
    @endif
@endpush
