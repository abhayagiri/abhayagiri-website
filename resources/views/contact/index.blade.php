@extends('layouts.app')

@section('title', __('contact.contact'))

@section('main')
    <contact-options
        :preamble="{{ json_encode($preamble) }}"
        :contact-options="{{ json_encode($contactOptions) }}"
        :initial-contact-option="{{ json_encode($contactOption) }}"
    ></contact-options>
@endsection
