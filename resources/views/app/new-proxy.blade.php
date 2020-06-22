@extends('layouts.app')

@section('title', app('pages')->current()->title)

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/react.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('/js/react.js') }}"></script>
@endpush
