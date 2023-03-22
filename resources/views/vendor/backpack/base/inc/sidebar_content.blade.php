<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>

@foreach (config('admin.groups') as $group)
    <li class="nav-item"><a class="nav-link disabled"><strong>{{ $group['label'] }}</strong></a></li>
    @foreach (config('admin.models') as $model)
        @if (($model['group'] === $group['name']) && (!array_get($model, 'super_admin', false) || backpack_user()->is_super_admin))
            <li class="nav-item"><a class="nav-link" href="{{ backpack_url(array_get($model, 'path', $model['name'])) }}">
                <i class="fa fa-{{ $model['icon'] }}"></i>
                <span>{{ array_get($model, 'label', title_case(str_replace('-', ' ', $model['name']))) }}</span>
            </a></li>
        @endif
    @endforeach
@endforeach

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>