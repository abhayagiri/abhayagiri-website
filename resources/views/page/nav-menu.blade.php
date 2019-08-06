<div class="nav-container container">
    <div id="nav" style="display: none;">
        <i class="fa fa-sort-asc arrow"></i>
        {{-- $navMenu is defined in app/Http/View/Composers/NavMenuComposer --}}
        @foreach ($navMenu as $item)
            <div class="brick">
                <a href="{{ $item->path }}">
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
