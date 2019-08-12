@extends('layouts.error')

@section('title', __('errors.forbidden'))
@section('code', '403')
@section('message', $exception->getMessage() ?: __('errors.forbidden'))
