@extends('layouts/minimal')

@section('title', __('admin.login.title'))

@section('content')

<h1 class="mb-5 cover-heading text-center">{{ __('admin.login.title') }}</h1>

@if (session('status'))
    <p class="alert alert-danger">
        {{ session('status') }}
    </p>
@endif

<p class="text-center">
    <a href="{{ route('login.google') }}" class="btn btn-lg btn-primary mr-3">
        <i class="fab fa-google"></i> {{ __('admin.login.with_google') }}
    </a>
    @if (\App\Util::devBypassAvailable())
        <a href="{{ route('login.devBypass') }}" class="btn btn-lg btn-warning mr-3">
            {{ __('admin.login.dev_bypass') }}</a>
    @endif
    <a href="{{ lp('/') }}" class="btn btn-lg btn-secondary">{{ __('common.return_home') }}</a>
</p>

@endsection
