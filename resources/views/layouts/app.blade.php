<!doctype html>
<html lang="{{ session('locale') }}">
<x-layout.head />
<body>
<div class="main-wrapper">
    <x-layout.right_bar />
{{--    <x-layout.vertical_menu />--}}

    <div class="page-wrapper">

        <x-layout.header />

        @yield('content')

        <x-layout.footer />
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
