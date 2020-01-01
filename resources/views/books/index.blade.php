@extends('layouts.app')

@include('app.index-breadcrumb-title', ['items' => $books])

@section('main')

    @if ($hasBooksInCart)
        <div class="alert alert-primary" role="alert">
            {{ __('books.books_selected') }}
            <a href="{{ lp(route('books.cart.show', null, false)) }}" class="alert-link">
                {{ __('books.view_selected') }}
            </a>
        </div>
    @endif

    @foreach ($books as $i => $book)
        @include('books.article')
    @endforeach

    @include('app.pagination', ['items' => $books])

@endsection
