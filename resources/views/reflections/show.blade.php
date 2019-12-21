@extends('layouts.app')

@section('title', $reflection->title)

@breadcrumb($reflection->title)

@section('main')

    @include('app.article', ['article' => $reflection])
    @include('app.back-to')

@endsection
