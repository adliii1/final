<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Klinik Online</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.doctor.index') }}" class="nav-link">Dokter</a></li>
                        <li class="nav-item"><a href="{{ route('admin.schedules.index') }}" class="nav-link">Jadwal</a></li>
                    @else
                        <li class="nav-item"><a href="{{ route('user.home') }}" class="nav-link">Beranda</a></li>
                        <li class="nav-item"><a href="{{ route('user.booking.index') }}" class="nav-link">Booking</a></li>
                        <li class="nav-item"><a href="{{ route('user.booking.history') }}" class="nav-link">Riwayat</a></li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn nav-link" type="submit"
                                style="border: none; background: none; padding: 0.5rem 1rem;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>