{{-- $page, $pages, $otherLngData is defined in ViewServiceProvider --}}

<div id="header-heading">

    <a id="header-logo" href="{{ lp('/') }}">
        <img alt="{{ __('common.abhayagiri') }}" src="/img/ui/header-{{ Lang::getLocale() }}.jpg">
    </a>

    <a id="header-menu-button" href="{{ lp('/menu') }}">
        <img alt="{{ __('common.menu') }}" src="/img/ui/menu-{{ Lang::getLocale() }}.png">
        <span>
            <i class="fa fa-th"></i> {{ __('common.menu') }}
        </span>
    </a>

    <a id="header-search-button" href="{{ lp('/search') }}">
        <img alt="{{ __('common.search') }}" src="/img/ui/search-{{ Lang::getLocale() }}.png">
        <span>
            <i class="fa fa-search"></i> {{ __('common.search') }}
        </span>
    </a>

</div>

<div id="header-menu">
    <div class="frame">
        <i class="fa fa-sort-asc arrow"></i>
        @foreach ($pages as $p)
            <a{!! $p->current ? ' class="active"': '' !!} href="{{ lp($p->path) }}">
                <i class="fa {{ $p->icon }}"></i>
                <span>{{ $p->title }}</span>
            </a>
        @endforeach
    </div>
</div>

<div id="header-search">
    <instant-search-form></instant-search-form>
    {{-- <search-form></search-form> --}}
</div>

<img id="header-banner" alt="{{ $page->title }}" src="{{ $page->bannerPath }}">

<div id="header-footing" class="page-{{ $page->slug }}">

    <a id="header-page" href="{{ lp($page->path) }}">
        <i class="fa {{ $page->icon }}"></i>
        <span class="title">{{ $page->title }}</span>
        @if ($page->subtitle)
            <span class="subtitle">{{ $page->subtitle }}</span>
        @endif
    </a>

    <a id="header-language" href="{{ $otherLngData->path }}">
        <span class="flag {{ $otherLngData->cssFlag }}"></span>
        <span class="text">{{ __('common.' . $otherLngData->transKey, [], $otherLngData->lng ) }}</span>
    </a>

</div>
