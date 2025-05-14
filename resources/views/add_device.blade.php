@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Add Device</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle>
                        <i data-feather="calendar" class="text-primary"></i>
                    </span>
                    <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
                </div>
            </div>
        </div>

        <!-- Card Formular -->
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Device Information</h6>
                        <form class="forms-sample" method="POST" action="{{ route('device.store') }}">
                            @csrf

                            <!-- Nume Dispozitiv -->
                            <div class="mb-3">
                                <label for="device_name" class="form-label">Device Name</label>
                                <input type="text"
                                       class="form-control @error('device_name') is-invalid @enderror"
                                       id="device_name"
                                       name="device_name"
                                       placeholder="Enter device name"
                                       value="{{ old('device_name') }}">
                                @error('device_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- MAC Address -->
                            <div class="mb-3">
                                <label for="mac_address" class="form-label">MAC Address</label>
                                <input type="text"
                                       class="form-control @error('mac_address') is-invalid @enderror"
                                       id="mac_address"
                                       name="mac_address"
                                       placeholder="00:1A:2B:3C:4D:5E"
                                       value="{{ old('mac_address') }}">
                                @error('mac_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{ route('home') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')


@endpush
