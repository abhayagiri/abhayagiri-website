<!-- TODO Copied (more or less) from react code -->

<div id="language-switch">
    <a href="/th">
        <span><span class="flag flag-th"></span>&nbsp;ภาษาไทย</span>
    </a>
    <!-- TODO language switch -->
</div>

<div id="header" class="clearfix" role="banner">

<div class="header-container container">
    <div class="header-logo"><a href="{{ lp('/') }}"><img src="/img/ui/header-en.jpg"></a></div>
    <div class="btn-container">
        <div class="btn-search float-right"><img class="header-nav-button" src="/img/ui/search-en.png"></div>
        <!-- TODO search -->
        <div class="btn-menu float-right"><img class="header-nav-button" src="/img/ui/menu-en.png"></div>
        <!-- TODO menu -->
    </div>
</div>

<div class="btn-mobile-container">
    <div class="btn-group">
        <button class="btn btn-large btn-secondary btn-mobile-menu header-nav-button">
            <i class="fa fa-th header-nav-button"></i>{{ __('common.menu') }}
            <!-- TODO menu -->
        </button>
        <button class="btn btn-large btn-secondary btn-mobile-search header-nav-button">
            <i class="fa fa-search header-nav-button"></i>{{ __('common.search') }}
            <!-- TODO search -->
        </button>
    </div>
</div>

<div class="search search-hidden">
<!-- TODO search -->
</div>

<div id="banner"><img src="/img/banner/gallery.jpg"></div>

<div id="breadcrumb-container">
    <div class="container">
        <ol class="breadcrumb">
            <!-- TODO breadcrumbs -->
            <li class="breadcrumb-item "><a href="{{ lp ('/') }}">Home</a></li>
            <li class="breadcrumb-item breadcrumb-navpage">{{ $subpage->title }}</li>
        </ol>
    </div>
</div>

</div>
