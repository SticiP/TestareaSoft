@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .select2-dropdown {
            z-index: 9999 !important;
        }

        .chart-box {
            flex: 1 1 calc(50% - 1rem);
            max-width: calc(50% - 1rem);
            min-width: 400px;
        }

        .card {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
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
                           data-input disabled="">
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Add Chart
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Chart.js</li>
                        <li><a class="dropdown-item add-chart" href="#" data-lib="chartjs" data-type="line">Line</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="grid-example" class="d-flex flex-wrap gap-4">
            {{-- Aici vor fi injectate card-urile cu grafice --}}
        </div>
    </div>

    <div class="modal fade" id="chartConfigModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="chartConfigForm" class="modal-content" method="POST" action="{{ route('chart.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Configure Chart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="lib" id="chart-lib">
                    <input type="hidden" name="type" id="chart-type">
                    <div class="mb-3">
                        <label class="form-label">Select Devices</label>
                        <select
                            name="devices[]"
                            class="js-example-basic-multiple form-select"
                            multiple="multiple"
                            data-width="100%"
                            id="devices">
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Sensors</label>
                        <select
                            name="sensors[]"
                            id="sensors"
                            class="form-select" data-width="100%">
                            <option value="a1">a1</option>
                            <option value="a2">a2</option>
                            <option value="d1">d1</option>
                            <option value="d2">d2</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date From</label>
                        <div class="input-group flatpickr" id="date-from-container">
                            <input type="text" class="form-control" placeholder="Select date & time"
                                   name="date_from" id="date-from">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                        </div>
                    </div>

                    <!-- Date To -->
                    <div class="mb-3">
                        <label class="form-label">Date To</label>
                        <div class="input-group flatpickr" id="date-to-container">
                            <input type="text" class="form-control" placeholder="Select date & time"
                                   name="date_to" id="date-to">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                        </div>
                    </div>

                    <!-- Switch -->
                    <div class="form-check form-switch mb-2">
                        <input name="current_time" type="checkbox" class="form-check-input" id="currentDateTimeSwitch" checked="">
                        <label class="form-check-label" for="currentDateTimeSwitch">Use Current Date/Time</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Chart</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="/vendors/sortablejs/Sortable.min.js"></script>
    <script src="/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/vendors/chartjs/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Plugin js for this page -->
    <script src="/vendors/select2/select2.min.js"></script>
    <!-- End plugin js for this page -->

    <!-- Custom js for this page -->
    <script src="/js/select2.js"></script>
    <!-- End custom js for this page -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inițializare Date From (azi 00:00)
            const dateFrom = flatpickr("#date-from", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultDate: new Date(new Date().setHours(0,0,0,0))
            });

            // Inițializare Date To (data/ora curentă)
            const dateTo = flatpickr("#date-to", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultDate: new Date()
            });

            // Toggle switch logic
            const switchInput = document.getElementById('currentDateTimeSwitch');
            const dateToInput = document.getElementById('date-to');

            switchInput.addEventListener('change', function() {
                const isChecked = this.checked;

                // Setare disabled și stiluri pentru temă întunecată
                dateToInput.disabled = isChecked;
                dateToInput.style.backgroundColor = isChecked ? '#2d3436' : ''; // gri închis
                dateToInput.style.color = isChecked ? '#ffffff' : ''; // text alb

                if (isChecked) {
                    dateTo.setDate(new Date());
                }
            });

            // Stare inițială
            dateToInput.disabled = true;
            dateToInput.style.backgroundColor = '#2d3436';
            dateToInput.style.color = '#ffffff';
        });

        var csrfToken = '{{ csrf_token() }}';
        var removeRoute = '{{ route('chart.remove') }}';
        const MAX_SELECTIONS = 4;

        $(function () {
            // initialize Sortable
            new Sortable(document.getElementById('grid-example'), {animation: 150, handle: '.card-header'});

            // load devices on modal open
            $('#chartConfigModal').on('show.bs.modal', () => {
                const chartType = $('#chart-type').val();

                if (chartType === 'realtime') {
                    $('#date-from-container, #date-to-container').closest('.mb-3').hide();
                } else {
                    $('#date-from-container, #date-to-container').closest('.mb-3').show();
                }
                $.getJSON('/device/get', data => {
                    let opts = data.map(d => `<option value="${d.id}">${d.device_name}</option>`).join('');
                    $('#devices').html(opts);
                    // $('#sensors').html('');
                });
            });

            // // when devices change, load sensors
            // $('#devices').on('change', function () {
            //     let devs = $(this).val();
            //     if (!devs || !devs.length) return;
            //     $('#sensors_type').html('');
            //     $.ajax({
            //         url: 'sensors/type', type: 'GET', data: {device_ids: devs}, success: sensorTypes => {
            //             console.log("sensorTypes : ");
            //             console.log(sensorTypes);
            //
            //             let opts = sensorTypes.map(st =>
            //                 `<option value="${st.id}">${st.name}</option>`
            //             ).join('');
            //             $('#sensors_type').html(opts);
            //             $('#sensors_type').trigger('change');
            //         }
            //     });
            // });

            // $('#sensors_type').on('change', function () {
            //     let devs = $('#devices').val();
            //     let type_id = $(this).val();
            //     console.log("devs " + devs);
            //     console.log("type_id " + type_id);
            //     $('#sensors').html('');
            //     if (!devs || !devs.length) return;
            //     $.ajax({
            //         url: '/sensors/get',
            //         type: 'GET',
            //         data: {
            //             device_ids: devs,
            //             type_id: type_id
            //         },
            //         success: sensors => {
            //             console.log("sensors : ");
            //             console.log(sensors);
            //             let opts = sensors.map(s => `
            //                 <option value="${s.id}">${s.sensor_name}</option>
            //             `).join('');
            //             $('#sensors').html(opts);
            //         },
            //         error: error => {
            //             console.error('Eroare:', error);
            //         }
            //     });
            // });

            $('body').prepend(`
                <div id="custom-alert-container"
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                </div>
            `);

            // $('#sensors').on('select2:select select2:unselect', function (e) {
            //     const selected = $(this).val() || [];
            //
            //     if (selected.length >= MAX_SELECTIONS + 1 && e.type === 'select2:select') {
            //         e.preventDefault();
            //
            //         const alertHtml = `
            //             <div class="alert alert-danger alert-dismissible fade show" role="alert">
            //                 You can select maximum ${MAX_SELECTIONS} sensors!
            //                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            //             </div>
            //         `;
            //
            //         $('#custom-alert-container').html(alertHtml);
            //
            //         // Elimină selecția nouă dacă este cazul
            //         const newOption = $(e.params.data.element);
            //         newOption.prop('selected', false);
            //         $(this).trigger('change.select2');
            //
            //         setTimeout(() => {
            //             $('#custom-alert-container .alert').alert('close');
            //         }, 5000);
            //     }
            // });

            // open config modal with lib & type
            $('.add-chart').on('click', function (e) {
                e.preventDefault();
                $('#chart-lib').val($(this).data('lib'));
                $('#chart-type').val($(this).data('type'));

                // Forțează actualizarea vizibilității câmpurilor
                const chartType = $('#chart-type').val();
                if (chartType === 'realtime') {
                    $('#date-from-container, #date-to-container').closest('.mb-3').hide();
                } else {
                    $('#date-from-container, #date-to-container').closest('.mb-3').show();
                }

                new bootstrap.Modal($('#chartConfigModal')).show();
            });

            var configs = @json($configs);

            // Parcurge fiecare configurație și creează un grafic
            configs.forEach(function (config) {
                // Creează un ID unic pentru fiecare grafic
                var chartId = 'chart_' + config.id;

                // Adaugă un card cu canvas-ul pentru grafic
                var chartCard = $(
                    `<div class="chart-box d-flex align-items-center justify-content-center">
                        <div class="card w-100 h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">${config.title || 'Chart'}</h6>
                                <form action="${removeRoute}" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="chart_id" value="${config.id}">
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                        <i data-feather="x"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="${chartId}"></canvas>
                            </div>
                        </div>
                    </div>`
                );

                // Adaugă cardul în container
                $('#grid-example').append(chartCard);

                feather.replace();

                // Parsează configurația stocată în coloana "data" și creează graficul
                var chartConfig = JSON.parse(config.data);
                new Chart(document.getElementById(chartId).getContext('2d'), chartConfig);
            });

        });

        function removeCard(btn) {
            $(btn).closest('.chart-box').remove();
        }
    </script>
@endpush
