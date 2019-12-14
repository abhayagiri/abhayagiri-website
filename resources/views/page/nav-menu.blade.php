<div class="nav-container container">
    <div id="nav" style="display: none;">
        <i class="fa fa-sort-asc arrow"></i>
        {{-- $pageMenu is defined in PageComposer --}}
        @foreach ($pageMenu as $item)
            <div class="brick">
                <a href="{{ lp($item->path) }}">
                    <div class="btn-nav{{ $item->active ? ' active' : '' }}">
                        <i class="fa {{ $item->icon }}"></i>
                        <br>
                        <span class="title-icon">{{ $item->title }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
