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

        <div
            class="flot-chart"
            id="flotRealTime"
            data-sensor-id="9408"
            style="width:800px; height:400px;">
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
    <script src="/vendors/jquery.flot/jquery.flot.time.js"></script>
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
        $(function() {
            'use strict';

            var colors = {
                primary    : "#6571ff",
                gridBorder : "rgba(77, 138, 240, .15)"
                // … restul culorilor
            };

            var fontFamily = "'Roboto', Helvetica, sans-serif";

            // Real-Time Chart
            if ($("#flotRealTime").length) {
                // ID-ul senzorului pentru care afișăm date;
                // poți lua dintr-un atribut data-sensor-id sau din Blade:
                var sensorId       = $("#flotRealTime").data("sensor-id") || 1;
                var limitPoints    = 20;
                var updateInterval = 1000;

                // Opțiuni Flot
                var options = {
                    series: {
                        shadowSize: 0,
                        lines: {
                            show: true,
                            lineWidth: 1,
                            fill: false,
                            opacity: 0.1
                        }
                    },
                    xaxis: {
                        mode: "time",
                        timezone: "browser"
                    },
                    yaxis: {
                        min: 0,
                        max: 150
                    },
                    grid: {
                        color: colors.primary,
                        borderColor: colors.gridBorder,
                        borderWidth: 1,
                        hoverable: true,
                        clickable: true
                    },
                    colors: [colors.primary]
                };

                // Inițializare plot cu date goale
                var plot = $.plot("#flotRealTime", [ [] ], options);

                function fetchAndRender() {
                    $.getJSON("/api/realtime/data", {
                        sensor_id: sensorId,
                        limit: limitPoints
                    })
                        .done(function(data) {
                            console.log(data);
                            // data e un array de [timestamp_ms, value], ordonat ascendent
                            plot.setData([ data ]);
                            plot.setupGrid();
                            plot.draw();
                        })
                        .fail(function(err) {
                            console.error("Eroare la încărcarea datelor realtime:", err);
                        });
                }

                // Prima încărcare + polling
                fetchAndRender();
                setInterval(fetchAndRender, updateInterval);
            }
        });
    </script>
@endpush
