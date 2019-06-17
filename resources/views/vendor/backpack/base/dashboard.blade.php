@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach (config('admin.models') as $model)
                @if (!array_get($model, 'super_admin', false) || \Auth::user()->is_super_admin)
                    <div style="display: inline-block; width: 9em; height: 10em; text-align: center; margin: 0.5em;">
                        <a style="display: block; width: 100%; height: 100%;" href="{{ url(config('backpack.base.route_prefix', 'admin') . '/' . array_get($model, 'path', $model['name'])) }}">
                            <i style="font-size: 8em;" class="fa fa-{{ $model['icon'] }} "></i><br>
                            <span>{{ title_case(str_replace('-', ' ', $model['name'])) }}</span>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
