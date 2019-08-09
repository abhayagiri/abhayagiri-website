<div class="header-container container">
    <div class="header-logo"><a href="{{ lp('/') }}"><img src="/img/ui/header-{{ Lang::getLocale() }}.jpg"></a></div>
    <div class="btn-container">
        <div class="btn-search float-right"><img class="header-nav-button" src="/img/ui/search-{{ Lang::getLocale() }}.png"></div>
        <div class="btn-menu float-right"><img class="header-nav-button" src="/img/ui/menu-{{ Lang::getLocale() }}.png"></div>
    </div>
</div>

<div class="btn-mobile-container">
    <div class="btn-group">
        <button class="btn btn-large btn-secondary btn-mobile-menu header-nav-button btn-menu">
            <i class="fa fa-th header-nav-button"></i> {{ __('common.menu') }}
        </button>
        <button class="btn btn-large btn-secondary btn-mobile-search header-nav-button btn-search">
            <i class="fa fa-search header-nav-button"></i> {{ __('common.search') }}
        </button>
    </div>
</div>
