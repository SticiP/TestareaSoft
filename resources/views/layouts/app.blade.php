<!doctype html>
<html lang="{{ session('locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/vendors/flatpickr/flatpickr.min.css">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <link rel="shortcut icon" href="/images/favicon.png" />

    @vite([
          'resources/css/app.css',
          'resources/css/style.css',
          'resources/css/style.css',
          'resources/js/app.js',
          'resources/js/template.js',
          'resources/js/dashboard-dark.js',
        ])
</head>

<body>
<div class="main-wrapper">
{{--    <x-layout.right_bar/>--}}
    @if(!($no_left_menu ?? false))
        <x-layout.vertical_menu />
    @endif

    <div class="page-wrapper">

        @if(!($no_header ?? false))
            <x-layout.header/>
        @endif

        @yield('content')

        @if(!($no_footer ?? false))
            <x-layout.footer/>
        @endif
    </div>

    <!-- core:js -->
    <script src="/vendors/core/core.js"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="/vendors/flatpickr/flatpickr.min.js"></script>
    <script src="/vendors/apexcharts/apexcharts.min.js"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="/vendors/feather-icons/feather.min.js"></script>
</div>
</body>
</html>
