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
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
          <li><a href="{{ url('admin/authors') }}"><i class="fa fa-tag"></i> <span>Authors</span></a></li>
          <li><a href="{{ url('admin/blobs') }}"><i class="fa fa-cog"></i> <span>Blobs</span></a></li>
          <li><a href="{{ url('admin/books') }}"><i class="fa fa-tag"></i> <span>Books</span></a></li>
          <li><a href="{{ url('admin/languages') }}"><i class="fa fa-tag"></i> <span>Languages</span></a></li>
          <li><a href="{{ url('admin/news') }}"><i class="fa fa-tag"></i> <span>News</span></a></li>
          <li><a href="{{ url('admin/playlists') }}"><i class="fa fa-tag"></i> <span>Playlists</span></a></li>
          <li><a href="{{ url('admin/setting') }}"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
          <li><a href="{{ url('admin/subject-groups') }}"><i class="fa fa-tag"></i> <span>Subject Groups</span></a></li>
          <li><a href="{{ url('admin/subjects') }}"><i class="fa fa-tag"></i> <span>Subjects</span></a></li>
          <li><a href="{{ url('admin/tags') }}"><i class="fa fa-tag"></i> <span>Tags</span></a></li>
          <li><a href="{{ url('admin/talk-types') }}"><i class="fa fa-tag"></i> <span>Talk Types</span></a></li>
          <li><a href="{{ url('admin/talks') }}"><i class="fa fa-tag"></i> <span>Talks</span></a></li>
          @if (\Auth::user()->is_super_admin)
            <li><a href="{{ url('admin/users') }}"><i class="fa fa-tag"></i> <span>Users</span></a></li>
          @endif


          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
