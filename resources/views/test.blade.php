@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/vendors/select2/select2.min.css">
    <style>
        .select2-dropdown {
            z-index: 9999 !important;
        }

        .chart-box {
            flex: 1 1 calc(50% - 1rem);
            max-width: calc(50% - 1rem);
            min-width: 400px;
            /*height: 500px;*/
        }

        .card {
            height: 100%;
            width: 100%; /* Adaugă asta pentru siguranță */
            display: flex;
            flex-direction: column; /* Foarte important! */
        }

        .card-body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        /*canvas {*/
        /*    width: 100% !important;  !* Umple întreaga lățime a cardului *!*/
        /*    height: 100% !important; !* Umple întreaga înălțime a cardului *!*/
        /*    object-fit: fill; !* Umple tot spațiul, fără a păstra proporția 1:1 *!*/
        /*}*/

        .card-header {
            width: 100%; /* Asigură că header-ul ocupă toată lățimea */
            padding: 0.5rem 1rem;
            background-color: #343a40; /* Poți ajusta pentru temă dark/light */
            color: white;
            border-bottom: 1px solid #444;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

    </style>
@endpush

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                        <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i
                                data-feather="calendar" class="text-primary"></i></span>
                    <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date"
                           data-input>
                </div>
            </div>
        </div>


        <div class="d-flex gap-2 flex-wrap" id="grid-example">
            <div class="chart-box d-flex align-items-center justify-content-center">
                <div class="card w-100 h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Line Chart</h6>
                        <form action="{{ route('chart.remove') }}" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                <i data-feather="x"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartjsLine"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-box d-flex align-items-center justify-content-center">
                <div class="card w-100 h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Line Chart</h6>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeCard(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartjsBar"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-box d-flex align-items-center justify-content-center">
                <div class="card w-100 h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Line Chart</h6>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeCard(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartjsDoughnut"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="/vendors/sortablejs/Sortable.min.js"></script>
    <script src="/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/vendors/chartjs/Chart.min.js"></script>
    <script src="/vendors/jquery.flot/jquery.flot.js"></script>
    <script src="/vendors/jquery.flot/jquery.flot.resize.js"></script>
    <script src="/vendors/jquery.flot/jquery.flot.pie.js"></script>
    <script src="/vendors/jquery.flot/jquery.flot.categories.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>

    <!-- Plugin js for this page -->
    <script src="/vendors/select2/select2.min.js"></script>
    <!-- End plugin js for this page -->

    <!-- Custom js for this page -->
    <script src="/js/select2.js"></script>
    <script src="/js/chartjs-dark.js"></script>
    <script src="/js/sortablejs-dark.js"></script>
    <!-- End custom js for this page -->

    <script>

    </script>
@endpush
