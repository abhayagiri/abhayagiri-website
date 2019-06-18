<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

<li>
    <a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span>
    </a>
</li>

<li>
    <a href="{{ backpack_url('/elfinder') }}">
        <i class="fa fa-files-o"></i> <span>File manager</span>
    </a>
</li>

@foreach (config('admin.groups') as $group)
    <li class="treeview">
        <a href="#"><i class="fa fa-{{ $group['icon'] }}"></i> <span>{{ $group['label'] }}</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @foreach (config('admin.models') as $model)
                @if ($model['group'] === $group['name'])
                    @if (!array_get($model, 'super_admin', false) || backpack_user()->is_super_admin)
                        <li>
                            <a href="{{ backpack_url(array_get($model, 'path', $model['name'])) }}">
                                <i class="fa fa-{{ $model['icon'] }} "></i>
                                <span>{{ array_get($model, 'label', title_case(str_replace('-', ' ', $model['name']))) }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </li>
@endforeach
