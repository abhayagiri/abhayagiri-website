@extends('layouts.app')

@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<div class="container">
    <div class="row">
        <a href="/admin/login/google">Login with Google</a>.
    </div>
    @if (\App\Util::devBypassAvailable())
        <div class="row">
            <a href="/admin/login/dev-bypass">Development Bypass</a>.
        </div>
    @endif
    <div class="row">
        <a href="/">Return</a>.
    </div>
</div>
@endsection
