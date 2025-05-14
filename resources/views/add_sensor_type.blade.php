@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Add Device</h4>
            </div>
        </div>

        <form class="forms-sample" method="POST" action="{{ route('sensor.type.store') }}">
            @csrf
            <div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:1050; width: 50%;"></div>
            <div class="row" id="main">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card h-100"> <!-- Adăugat h-100 pentru înălțime maximă -->
                        <div class="card-header">
                            <h6 class="mb-0">Add Sensor Type</h6>
                        </div>
                        <div class="card-body d-flex flex-column"> <!-- Flex container -->
                            <!-- Device fields -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Sensor Type Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter sensor type name" value="{{ old('name') }}">
                            </div>

                            <!-- Zona care se extinde automat pentru a "împinge" butoanele în jos -->
                            <div class="flex-grow-1"></div>

                            <!-- Butoanele -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('home') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
@endpush
