@extends('layouts.minimal')

@section('content')

<h1>@yield('code')</h1>
<h2>@yield('message')</h2>

<p>{{ __('errors.apologies') }}</p>

<p>
    <a href="{{ lp('/contact') }}" class="btn btn-lg btn-primary mr-3">
        {{ __('errors.tell_us') }}
    </a>
    <a href="{{ lp('/') }}" class="btn btn-lg btn-secondary">
        {{ __('common.return_home') }}
    </a>
</p>

@endsection
