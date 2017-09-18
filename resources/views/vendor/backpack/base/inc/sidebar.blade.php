@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="https://placehold.it/160x160/00a65a/ffffff/&text={{ mb_substr(Auth::user()->name, 0, 1) }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">{{ trans('backpack::base.administration') }}</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-home"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
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
