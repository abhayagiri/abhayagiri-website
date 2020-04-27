@extends('layouts.app')

@section('title', __('contact.contact'))

@section('main')
    <div class="contact container">
        <legend>{{ tp($contactOption, 'name') }}</legend>

        <div class="row">
            <div class="col-sm-12">
                <div class="contact-text">{{ tp($contactOption, 'body') }}</div>

                @if($contactOption->active)
                    <div class='contact container'>
                        <form method="POST" action="{{ route('contact.send-message', $contactOption) }}" class="contact-form form-horizontal">
                            @csrf
                            <input type="hidden" name="contact_option" value="{{ $contactOption->id }}">

                            <hr class="contact-separator" />

                            <div class='form-group row'>
                                <label class='control-label col-md-2 text-right' htmlFor="name"><b>{{ __('contact.name') }}</b></label>
                                <div class='col-md-6'>
                                    <input type="text" id="name" name="name" class='form-control' required value="{{ old('name') }}" />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='control-label col-md-2 text-right' htmlFor="inputIcon"><b>{{ __('contact.email-address') }}</b></label>
                                <div class='col-md-6'>
                                    <div class='input-prepend'>
                                        <span class='add-on'><i class='icon-envelope'></i></span>
                                        <input class='form-control' id="email" name="email" type="email" required value="{{ old('email') }}" />
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='control-label col-md-2 text-right' htmlFor="email"><b>{{ __('contact.message') }}</b></label>
                                <div class='col-md-6'>
                                    <textarea id="message" name="message" rows="12" class="form-control" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='control-label col-md-2 text-right'></label>
                                <div class='col-md-6'>
                                    <recaptcha sitekey="{{ config('captcha.sitekey') }}" language="{{ Lang::getLocale() }}"></recaptcha>
                                    @error('recaptcha')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class='form-group row'>
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-large btn-primary">
                                        {{ __('contact.send-message') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <div class="col-sm-12">
                <a href="{{ lp('contact') }}" class="btn btn-secondary">{{ __('contact.back') }}</a>
            </div>
        </div>
    </div>
@endsection
