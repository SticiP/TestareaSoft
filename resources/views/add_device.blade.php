@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Add Device</h4>
            </div>
        </div>

        <form class="forms-sample" method="POST" action="{{ route('device.store') }}">
            @csrf
            <div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:1050; width: 50%;"></div>
            <div class="row" id="main">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card h-100"> <!-- Adăugat h-100 pentru înălțime maximă -->
                        <div class="card-header">
                            <h6 class="mb-0">Device Information</h6>
                        </div>
                        <div class="card-body d-flex flex-column"> <!-- Flex container -->
                            <!-- Device fields -->
                            <div class="mb-3">
                                <label for="device_name" class="form-label">Device Name</label>
                                <input type="text" class="form-control" id="device_name" name="device_name" placeholder="Enter device name" value="{{ old('device_name') }}">
                            </div>
                            <div class="mb-3">
                                <label for="mac_address" class="form-label">MAC Address</label>
                                <input type="text" class="form-control" id="mac_address" name="mac_address" placeholder="00:1A:2B:3C:4D:5E" value="{{ old('mac_address') }}">
                            </div>

                            <!-- Zona care se extinde automat pentru a "împinge" butoanele în jos -->
                            <div class="flex-grow-1"></div>

                            <!-- Butoanele -->
                            <div class="d-flex justify-content-end mt-3">
                                <a href="{{ route('sensor.type.add') }}" class="btn btn-outline-light me-2">Add Sensor Type</a>
                                <button type="button" class="btn btn-outline-primary btn-sm me-2" id="add-input-sensor">Add Input Sensor</button>
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
    <script>
        let inputSensorCount = 0;
        const sensorTypes = {!! json_encode($sensor_types) !!};
        const main = document.getElementById('main');

        function createNavCard() {
            const card = document.createElement('div');
            card.classList.add('col-md-4', 'grid-margin', 'stretch-card');
            card.id = 'input-card';
            card.innerHTML = `
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Input Sensors</h6>
                    <span class="badge bg-primary">Tabs: ${inputSensorCount}</span>
                </div>
                <ul class="nav nav-tabs" id="inputNav" role="tablist"></ul>
                <div class="card-body p-0">
                    <div class="tab-content p-3" id="inputTabContent"></div>
                </div>
            </div>
        `;
            main.appendChild(card);
        }

        function addSensorTab() {
            const index = inputSensorCount;
            if (index === 0) createNavCard();

            // update badge count
            document.querySelector('#input-card .badge').textContent = `Tabs: ${index + 1}`;

            const nav = document.getElementById('inputNav');
            const content = document.getElementById('inputTabContent');
            const tabId = `input-sensor-${index}`;

            // Nav item
            const li = document.createElement('li');
            li.classList.add('nav-item');
            li.id = `${tabId}-tab-item`;
            li.innerHTML = `
            <a class="nav-link" id="${tabId}-tab" data-bs-toggle="tab" href="#${tabId}" role="tab">
                ${index + 1}
                <span class="ms-1 text-danger" style="cursor:pointer;">&times;</span>
            </a>
        `;
            nav.appendChild(li);

            // Content pane
            const pane = document.createElement('div');
            pane.classList.add('tab-pane', 'fade');
            if (index === 0) pane.classList.add('show', 'active');
            pane.id = tabId;
            pane.setAttribute('role', 'tabpanel');
            pane.innerHTML = `
            <div class="p-3">
                <div class="mb-3">
                    <label class="form-label">Sensor Type</label>
                    <select name="input_sensors[${index}][sensor_type_id]" class="form-control" required>
                        <option value="">Select Type</option>
                        ${sensorTypes.map(st => `<option value="${st.id}">${st.name}</option>`).join('')}
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sensor Name</label>
                    <input type="text" name="input_sensors[${index}][sensor_name]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pin Number</label>
                    <input type="number" name="input_sensors[${index}][pin_number]" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="input_sensors[${index}][is_output]" id="is_output_${index}">
                    <label class="form-check-label" for="is_output_${index}">Also Output Sensor</label>
                </div>
            </div>
        `;
            content.appendChild(pane);

            // Activate new tab
            new bootstrap.Tab(document.querySelector(`#${tabId}-tab`)).show();

            // Remove on close
            li.querySelector('span').addEventListener('click', (e) => {
                e.stopPropagation();
                removeSensorTab(index);
            });

            inputSensorCount++;
        }

        function removeSensorTab(index) {
            const tabId = `input-sensor-${index}`;
            const tabItem = document.getElementById(`${tabId}-tab-item`);
            const pane = document.getElementById(tabId);

            // Activate another if needed
            if (tabItem.querySelector('a').classList.contains('active')) {
                const sibling = tabItem.previousElementSibling || tabItem.nextElementSibling;
                if (sibling) new bootstrap.Tab(sibling.querySelector('a')).show();
            }

            tabItem.remove();
            pane.remove();
            inputSensorCount--;
            document.querySelector('#input-card .badge').textContent = `Tabs: ${inputSensorCount}`;

            if (inputSensorCount === 0) document.getElementById('input-card').remove();
        }

        document.getElementById('add-input-sensor').addEventListener('click', addSensorTab);
    </script>
    <script>
        function showAlert(type, message) {
            // type: 'success' sau 'danger'
            const wrapper = document.getElementById('alert-container');
            // Creăm alerta
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
            // Adăugăm în container
            wrapper.append(alert);
            // O scoatem automat după 5s
            setTimeout(() => {
                // Bootstrap 5: dismiss manuț
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form.forms-sample');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Construim FormData din tot formularul (inclusiv tab-uri dinamice)
                const formData = new FormData(form);
                const csrfToken = document.querySelector('input[name="_token"]').value;

                console.log(csrfToken);

                fetch("{{ route('device.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            // Dacă serverul returnează 422 sau alt cod de eroare,
                            // poți extrage erorile astfel:
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.location.href = '/'
                    })
                    .catch(error => {
                        console.error('Eroare la salvare:', error);
                        // dacă back transmite erori de validare în error.errors
                        const msg = error.message || (error.errors
                            ? Object.values(error.errors).flat().join('<br>')
                            : 'A apărut o eroare.');
                        showAlert('danger', msg);
                    });
            });
        });
    </script>
@endpush
