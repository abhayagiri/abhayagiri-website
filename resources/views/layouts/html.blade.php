<!DOCTYPE html>
<html lang="{{ Lang::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ __('common.abhayagiri_monastery') }}</title>
    <link rel="stylesheet" href="{{ mix('/mix/css/app.css') }}">
    @stack('styles')
</head>
<body>
    @yield('body')
    @include('page.loading')
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        window.Locale = <?php echo json_encode(Lang::locale()); ?>;
    </script>
    <script src="{{ mix('/mix/js/manifest.js') }}"></script>
    <script src="{{ mix('/mix/js/vendor.js') }}"></script>
    <script src="{{ mix('/mix/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
