@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/vendors/select2/select2.min.css">
@endpush

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Sensor : {{ $sensor->sensor_name }}</h4>
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div
                            class="flot-chart"
                            id="flotRealTime"
                            data-sensor-id="{{ $sensor->id }}"
                            style="width:auto; height:400px;">
                        </div>
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

            $.ajaxSetup({ cache: false });

            // Real-Time Chart
            if ($("#flotRealTime").length) {
                // ID-ul senzorului pentru care afișăm date;
                // poți lua dintr-un atribut data-sensor-id sau din Blade:
                var sensorId       = $("#flotRealTime").data("sensor-id") || 1;
                var limitPoints    = 30;
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
                        max: 20
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
                    console.log('test1');
                    $.getJSON("/api/realtime/data", {
                        sensor_id: sensorId,
                        limit: limitPoints
                    })
                        .done(function(data) {
                            // 1) Calculează noul max, cu un +20%
                            const values = data.map(item => item[1]);
                            const maxData = Math.max(...values);
                            const newMax = maxData + maxData * 0.2;

                            // 2) Actualizează direct axa Y
                            var axes = plot.getAxes();
                            axes.yaxis.options.max = newMax;

                            // 3) Setează datele și redă graficul
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
