<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            Testarea<span>Soft</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">

            {{-- Add Devices --}}
            <li class="nav-item {{ url()->current() === route('device.add') ? 'active' : '' }}">
                <a href="{{ route('device.add') }}" class="nav-link">
                    <i class="link-icon" data-feather="plus"></i>
                    <span class="link-title">Add Devices</span>
                </a>
            </li>

            {{-- Dashboard --}}
            <li class="nav-item {{ url()->current() === route('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item nav-category">Devices</li>

            {{-- Lista de devices --}}
            @forelse($devices as $device)
                @php
                    // URL-ul așteptat pentru acest device
                    $deviceUrl = route('device.page', $device->id);
                @endphp
                <li class="nav-item {{ url()->current() === $deviceUrl ? 'active' : '' }}">
                    <a href="{{ $deviceUrl }}" class="nav-link">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">{{ $device->device_name }}</span>
                    </a>
                </li>
            @empty
                <li class="nav-item">
                    <span class="nav-link text-muted">No devices found</span>
                </li>
            @endforelse

        </ul>
    </div>
</nav>
