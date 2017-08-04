@extends('layouts.app')

@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<div class="container">
    <div class="row">
        <a href="/login/google">Login with Google</a>.
    </div>
    <div class="row">
        <a href="/">Return</a>.
    </div>
</div>
@endsection
