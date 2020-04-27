@extends('layouts.app')

@section('title', __('contact.contact'))

@section('main')
    <div class="contact container">
        <legend>{{ __('contact.contact') }}</legend>

        <div class="contact-text">{{ optional($preamble)->value }}</div>

        <div class="row">
            <div class="col-md-6">
                <ul class="contact-options list-group">
                    @foreach($contactOptions as $contactOption)
                        <a href="{{ lp('contact/' . $contactOption->slug) }}" class="list-group-item">{{ tp($contactOption, 'name') }}</a>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
