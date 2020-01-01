{{-- $pages is defined in ViewServiceProvider --}}
<div class="nav-container container">
    <div id="nav" style="display: none;">
        <i class="fa fa-sort-asc arrow"></i>
        @foreach ($pages as $page)
            <div class="brick">
                <a href="{{ lp($page->path) }}">
                    <div class="btn-nav{{ $page->current ? ' active' : '' }}">
                        <i class="fa {{ $page->icon }}"></i>
                        <br>
                        <span class="title-icon">{{ $page->title }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
