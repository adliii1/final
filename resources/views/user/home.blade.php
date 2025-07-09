@extends('layouts.app')
@section('title', 'Beranda Pasien')

@section('content')

    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
    <p>Silakan gunakan menu di atas untuk melakukan booking layanan klinik.</p>

    <!-- Status Antrian Real-time per Dokter -->
    <div class="row mb-4">
        @forelse($doctorsWithSchedule as $doctor)
            @php
                $currentQueue = $currentlyServed->get($doctor->id)?->first();
                $waitingCount = $waitingCounts->get($doctor->id, 0);
            @endphp
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm queue-card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>{{ $doctor->name }}</h6>
                        <small><i class="fas fa-stethoscope me-1"></i>{{ $doctor->specialist }}</small>
                    </div>
                    <div class="card-body text-center">
                        @if($currentQueue)
                            <div class="mb-2">
                                <span class="badge bg-success mb-1 pulse-animation">
                                    <i class="fas fa-play me-1"></i>Sedang Dilayani
                                </span>
                                <h4 class="text-primary mb-0 queue-number-display">{{ $currentQueue->queue_number }}</h4>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $currentQueue->booking->user->name }}
                                </small>
                            </div>
                        @else
                            <div class="mb-2">
                                <span class="badge bg-secondary mb-1">
                                    <i class="fas fa-pause me-1"></i>Tidak Ada Antrian
                                </span>
                                <h4 class="text-muted mb-0">-</h4>
                            </div>
                        @endif

                        <hr class="my-2">

                        <div class="d-flex justify-content-between text-sm">
                            <span><i class="fas fa-clock me-1"></i>Menunggu:</span>
                            <span class="badge bg-warning">
                                <i class="fas fa-users me-1"></i>{{ $waitingCount }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada jadwal dokter hari ini.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Status Antrian Pengguna -->
    @if($userQueue)
        <div class="w-full max-w-sm mx-auto rounded-2xl shadow-xl p-6 md:p-8 space-y-10 mb-5">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-800">Status Antrian Anda</h1>
                <p class="text-sm text-slate-600">{{ $userQueue->booking->schedule->doctor->name }} -
                    {{ $userQueue->booking->schedule->doctor->specialist }}</p>
            </div>

            <div class="bg-slate-50 border-2 border-slate-200 rounded-xl p-6 text-center">
                <p class="text-base font-semibold text-slate-600">
                    <i class="fas fa-ticket-alt me-2"></i>Nomor Antrian Anda
                </p>
                <p class="text-5xl font-bold text-slate-800 my-2 queue-number-display">{{ $userQueue->queue_number }}</p>

                @if($userQueue->status === 'waiting')
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-hourglass-half me-1"></i>Menunggu
                    </span>
                @elseif($userQueue->status === 'in_progress')
                    <span class="badge bg-success pulse-animation">
                        <i class="fas fa-play me-1"></i>Sedang Dilayani
                    </span>
                @endif
            </div>

            <div class="text-center pt-4">
                <p class="text-xs text-slate-400">Mohon perhatikan nomor antrian Anda. <br> Terima kasih telah menunggu.</p>

                @if($userQueue->status === 'waiting')
                    @php
                        $currentServedForDoctor = $currentlyServed->get($userQueue->booking->schedule->doctor->id)?->first();
                        $waitingForDoctor = $waitingCounts->get($userQueue->booking->schedule->doctor->id, 0);
                    @endphp

                    @if($currentServedForDoctor)
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <strong>Sedang dilayani:</strong> {{ $currentServedForDoctor->queue_number }}
                            </p>
                            <p class="text-xs text-blue-600">
                                Sisa antrian di depan Anda: {{ $waitingForDoctor - 1 }} orang
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @else
        <div class="w-full max-w-sm mx-auto rounded-2xl shadow-xl p-6 md:p-8 space-y-6 mb-5">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-800">Status Antrian</h1>
            </div>

            <div class="bg-slate-50 border-2 border-slate-200 rounded-xl p-6 text-center">
                <i class="fas fa-calendar-times text-4xl text-slate-400 mb-3"></i>
                <p class="text-lg font-semibold text-slate-600">Anda belum memiliki antrian hari ini</p>
                <p class="text-sm text-slate-500 mt-2">Silakan lakukan booking untuk mengambil nomor antrian</p>

                <a href="{{ route('user.booking.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Booking Sekarang
                </a>
            </div>
        </div>
    @endif

    <!-- Auto Refresh -->
    <script>
        // Auto refresh halaman setiap 30 detik untuk update status antrian
        let refreshTimer = setTimeout(function () {
            // Tambahkan loading indicator sebelum refresh
            const refreshElement = document.getElementById('refresh-counter');
            if (refreshElement) {
                refreshElement.textContent = 'Sedang memperbarui...';
                refreshElement.className = 'position-fixed bottom-0 end-0 m-3 p-2 bg-success text-white rounded small';
            }

            window.location.reload();
        }, 30000);

        // Tambahkan indikator refresh
        let refreshCounter = 30;
        const refreshElement = document.createElement('div');
        refreshElement.className = 'position-fixed bottom-0 end-0 m-3 p-2 bg-dark text-white rounded small';
        refreshElement.id = 'refresh-counter';
        refreshElement.style.zIndex = '1050';
        document.body.appendChild(refreshElement);

        const updateCounter = setInterval(function () {
            refreshCounter--;
            refreshElement.innerHTML = `<i class="fas fa-sync-alt me-1"></i>Refresh otomatis dalam ${refreshCounter}s`;

            if (refreshCounter <= 0) {
                clearInterval(updateCounter);
                refreshElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sedang refresh...';
                refreshElement.className = 'position-fixed bottom-0 end-0 m-3 p-2 bg-warning text-dark rounded small';
            }
        }, 1000);

        // Pause auto refresh jika user sedang interact dengan halaman
        let lastActivity = Date.now();

        document.addEventListener('click', function () {
            lastActivity = Date.now();
        });

        document.addEventListener('scroll', function () {
            lastActivity = Date.now();
        });

        // Manual refresh button
        refreshElement.addEventListener('click', function () {
            clearTimeout(refreshTimer);
            clearInterval(updateCounter);
            refreshElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memperbarui...';
            refreshElement.className = 'position-fixed bottom-0 end-0 m-3 p-2 bg-info text-white rounded small';
            window.location.reload();
        });

        // Keyboard shortcut untuk refresh (F5 atau Ctrl+R)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                clearTimeout(refreshTimer);
                clearInterval(updateCounter);
            }
        });
    </script>
@endsection