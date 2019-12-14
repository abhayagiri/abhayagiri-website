@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-md-12 quick-links">
            @foreach (config('admin.models') as $model)
                @if (!array_get($model, 'super_admin', false) || backpack_user()->is_super_admin)
                    <div><a href="{{ backpack_url(array_get($model, 'path', $model['name'])) }}">
                        <i style="font-size: 4em;" class="fa fa-{{ $model['icon'] }} "></i><br>
                        <span>{{ title_case(str_replace('-', ' ', $model['name'])) }}</span>
                    </a></div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
