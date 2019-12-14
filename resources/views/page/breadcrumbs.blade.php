<div id="breadcrumb-container">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ lp('/') }}">{{ __('common.home') }}</a>
            </li>
            @stack('breadcrumbs')
        </ol>
    </div>
</div>
