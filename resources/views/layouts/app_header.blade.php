<!-- TODO Copied (more or less) from react code -->

@include('page/language')

<div id="header" class="clearfix" role="banner">

@include('page/logo-and-buttons')
@include('page/nav-menu')
@include('page/search')
@include('page/banner')

<div id="breadcrumb-container">
    <div class="container">
        <ol class="breadcrumb">
            <!-- TODO breadcrumbs -->
            <li class="breadcrumb-item "><a href="{{ lp ('/') }}">{{ __('common.home') }}</a></li>
            <li class="breadcrumb-item breadcrumb-navpage">{{ $subpage->title }}</li>
        </ol>
    </div>
</div>

</div>
