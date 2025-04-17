<!doctype html>
<html lang="{{ session('locale') }}">

<x-layout.head />

<body data-sidebar="dark">

<!-- Begin page -->
<div id="layout-wrapper">

    <!-- ========== Header Start ========== -->
    <x-layout.header />
    <!-- Header End -->


    <!-- ========== Left Sidebar Start ========== -->
    <x-layout.vertical_menu />
    <!-- Left Sidebar End -->


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    @yield('content')
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- Right Sidebar -->
<x-layout.right_bar />
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="libs/jquery/jquery.min.js"></script>
<script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="libs/metismenu/metisMenu.min.js"></script>
<script src="libs/simplebar/simplebar.min.js"></script>
<script src="libs/node-waves/waves.min.js"></script>

<!-- apexcharts -->
<script src="libs/apexcharts/apexcharts.min.js"></script>
</body>
</html>
