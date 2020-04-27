@extends('layouts.app')

@section('title', __('contact.contact'))

@section('main')
    <div class="contact container">
        <legend>{{ tp($contactOption, 'name') }}</legend>

        <div class="row">
            <div class="col-sm-12">
                <div class="contact-text">{!! tp($contactOption, 'confirmation_html') !!}</div>

                <div>
                    <legend>{{ __('contact.your-message-title') }}</legend>
                    <blockquote className="blockquote user-message">{{ $formMessage }}</blockquote>
                </div>
            </div>
        </div>
    </div>
@endsection
