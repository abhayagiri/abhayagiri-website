<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
        @if (isset($pages))
           @hasSection('title')
             |
           @endif
           {{ $pages->current()->title }}
        @endif
        | {{ __('common.abhayagiri_monastery') }}</title>
    <link rel="stylesheet" href="{{ mix('/mix/css/app.css') }}">
    @stack('styles')
</head>
<body>
    @yield('body')
    @include('app.loading')
    <script>
        window.Laravel = {
            algoliaId: @json(config('scout.algolia.id')),
            algoliaPagesIndex: @json((new \App\Search\Pages())->searchableAs()),
            algoliaSearchKey: @json(\Algolia\ScoutExtended\Facades\Algolia::searchKey(\App\Search\Pages::class)),
            csrfToken: @json(csrf_token())
        };
        window.Locale = @json(\Lang::locale());
    </script>
    <script src="{{ mix('/mix/js/manifest.js') }}"></script>
    <script src="{{ mix('/mix/js/vendor.js') }}"></script>
    <script src="{{ mix('/mix/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
