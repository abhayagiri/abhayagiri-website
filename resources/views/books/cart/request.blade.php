@extends('layouts.app')

@section('title', __('books.book_request'))

@section('main')

    <section class="book-cart">

        <h1>{{ __('books.book_request') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ __('books.request_has_errors_see_below') }}
            </div>
        @endif

        <section class="important">
            <h2>
                <i class="fa fa-exclamation-triangle"></i>
                {{ __('books.important') }}
            </h2>
            {!! $informationHtml !!}
        </section>

        <section class="selection">
            <h2>
                <i class="fa fa-book"></i>
                {{ __('books.selection') }}
            </h2>
            @include('books.cart.selections', ['editable' => false])
            <a class="btn btn-light" role="button"
               href="{{ lp(route('books.index', null, false)) }}">
                ⇠ {{ __('books.select_additional_books') }}
            <a class="btn btn-light ml-5" role="button"
               href="{{ lp(route('books.cart.show', null, false)) }}">
                ⇠ {{ __('books.change_quantities') }}
            </a>
        </section>

        <section class="shipping">
            <h2>
                <i class="fa fa-truck"></i>
                {{ __('books.shipping') }}
            </h2>
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-1"></div>
                    <div class="col-xl-8 col-lg-9">
                        @include('books.cart.shipping')
                    </div>
                </div>
            </div>
        </section>

    </section>

@endsection
