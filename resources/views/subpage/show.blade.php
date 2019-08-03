@extends('layouts.app')

@section('title', $subpage->title)

@section('main')

<article>

{!! $subpage->body_html !!}

</article>

@endsection
