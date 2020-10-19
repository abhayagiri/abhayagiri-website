@extends('layouts.app')

@include('app.index-title', ['items' => $books])

@section('main')

    @if ($hasBooksInCart)
        <div class="alert alert-primary" role="alert">
            {{ __('books.books_selected') }}
            <a href="{{ lp(route('books.cart.show', null, false)) }}" class="alert-link">
                {{ __('books.view_selected') }}
            </a>
        </div>
    @endif

    @include('books.filter')

    @include('app.pagination', ['items' => $books, 'top' => true])

    @forelse($books as $i => $book)
        @include('books.article', ['abridge' => true])
    @empty
        <p class="p-2 text-center my-3">{{ __('books.no_filter_results') }}</p>
    @endforelse

    @include('app.pagination', ['items' => $books])

@endsection
