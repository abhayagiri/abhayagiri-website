@extends('errors.layout')

@section('title', __('errors.request_timeout'))
@section('code', '408')
@section('message', __('errors.request_timeout'))
