@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/vendors/select2/select2.min.css">
@endpush

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Device : {{ $device->device_name }}</h4>
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

        <!-- Card pentru Senzori de Intrare -->
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        Senzori de Intrare
                    </div>
                    <div class="card-body">
                        @if($device->inputSensors->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Sensor Name</th>
                                        <th>Sensor Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($device->inputSensors as $index => $sensor)
                                        <tr>
                                            <th>{{ $index + 1 }}</th>
                                            <td>{{ $sensor->sensor_name }}</td>
                                            <td>{{ $sensor->sensor_type_id }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('sensor.page', $sensor->id) }}" class="btn btn-info btn-sm">
                                                    Realtime View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Nu există senzori de intrare.</p>
                        @endif
                    </div>
                </div>
            </div>

        <!-- Card pentru Senzori de Ieșire -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card h-100">
            <div class="card-header bg-success text-white">
                Senzori de Ieșire
            </div>
            <div class="card-body">
                @if($device->outputSensors->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Sensor Name</th>
                                <th>Sensor Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($device->outputSensors as $index => $sensor)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $sensor->sensor_name }}</td>
                                    <td>{{ $sensor->sensor_type_id }}</td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            Send
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Nu există senzori de ieșire.</p>
                @endif
            </div>
        </div>
            </div>
        </div>


    </div>
@endsection

@push('js')
    <script src="/vendors/sortablejs/Sortable.min.js"></script>
@endpush
