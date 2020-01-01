@extends('emails.layout')

@section('content')

<h1>{{ $subject }}</h1>

<p>{{ __('common.date') }}: @datetime</p>

<hr>

@include('emails.book-cart.receipt')

@endsection
