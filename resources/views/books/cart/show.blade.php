@extends('layouts.app')

@section('title', __('books.book_selection'))

@breadcrumb(__('books.selection'))

@section('main')

    <section class="book-cart">

        <h1>{{ __('books.book_selection') }}</h1>

        <section class="selection">
            @include('books.cart.selections', ['editable' => true])
            <a class="btn btn-light" role="button"
               href="{{ lp(route('books.index', null, false)) }}">
                ⇠ {{ __('books.select_additional_books') }}
            <a class="btn btn-light pull-right" role="button"
               href="{{ lp(route('books.cart.submit', null, false)) }}">
                {{ __('books.request_selected_books') }} ⇢
            </a>
        </section>

    </section>

@endsection
