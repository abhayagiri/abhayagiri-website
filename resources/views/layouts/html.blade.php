<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ __('common.abhayagiri_monastery') }}</title>
    @stack('styles')
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
    </script>
</head>
<body>
    @yield('body')
    @stack('scripts')
</body>
</html>
