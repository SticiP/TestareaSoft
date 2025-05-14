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

    @stack('head')

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

    <!-- Layout styles -->
    <link rel="stylesheet" href="/css/demo2/style.css">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="/images/favicon.png" />

    @vite([
          'resources/css/app.css',
          'resources/js/app.js',
        ])

    <style>
        .alert {
            min-width: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
@if(session('success') || session('error') || session('warning') || session('info') || session('errors'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999; right: 30px; bottom: 20px;">

        @if(session('errors') && session('errors')->any())
            @foreach(session('errors')->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
@endif
<div class="main-wrapper">
{{--    <x-layout.right_bar/>--}}
    @if(!($no_left_menu ?? false))
        <x-layout.sidebar />
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
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="/vendors/feather-icons/feather.min.js"></script>
    <script src="/js/template.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="/js/dashboard-dark.js"></script>
    <!-- End custom js for this page -->

    @stack('js')
</div>
</body>
</html>
