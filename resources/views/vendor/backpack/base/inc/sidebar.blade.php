@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        @include('backpack::inc.sidebar_user_panel')

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
          @foreach (config('admin.groups') as $group)
            <li class="treeview">
              <a href="#"><i class="fa fa-{{ $group['icon'] }}"></i> <span>{{ $group['label'] }}</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                @foreach (config('admin.models') as $model)
                  @if ($model['group'] === $group['name'])
                    @if (!array_get($model, 'super_admin', false) || \Auth::user()->is_super_admin)
                      <li>
                        <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/' . array_get($model, 'path', $model['name'])) }}">
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
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
