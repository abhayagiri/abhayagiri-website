@extends('emails.layout')

@section('content')

<h1>{{ $subject }}</h1>

<p>{{ __('books.book_request_received_details') }}</p>

<hr>

@include('emails.book-cart.receipt')

@endsection
