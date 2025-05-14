<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            Testarea<span>Soft</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route('device.add') }}" class="nav-link">
                    <i class="link-icon" data-feather="plus"></i>
                    <span class="link-title">Add Devices</span>
                </a>
            </li>

            <li class="nav-item nav-category">Devices</li>
            @forelse($devices as $device)
                <li class="nav-item">
                    <a href="#" class="nav-link">
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
