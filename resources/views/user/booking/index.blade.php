@extends('layouts.app')
@section('title', 'Booking Jadwal')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Booking Jadwal Dokter') }}</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Peringatan jika user sudah memiliki antrian aktif --}}
                    @if(isset($existingQueue) && $existingQueue)
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Anda Sudah Memiliki Antrian Aktif</h5>
                            <p>Anda sudah memiliki antrian aktif dengan nomor <strong>{{ $existingQueue->queue_number }}</strong> 
                               untuk Dr. {{ $existingQueue->booking->schedule->doctor->name ?? 'N/A' }} 
                               pada tanggal {{ \Carbon\Carbon::parse($existingQueue->queue_date)->format('d/m/Y') }}.</p>
                            <p class="mb-0">
                                <a href="{{ route('user.queue.today') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Antrian Hari Ini
                                </a>
                            </p>
                        </div>
                    @endif

                    @if($schedules->count() > 0 && (!isset($existingQueue) || !$existingQueue))
                        <form method="POST" action="{{ route('user.booking.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="schedule_id" class="form-label">Pilih Jadwal Dokter:</label>
                                <select name="schedule_id" id="schedule_id" class="form-control" required>
                                    <option value="">-- Pilih Jadwal --</option>
                                    @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->doctor->name }} ({{ $schedule->doctor->specialist }}) | 
                                            {{ \Carbon\Carbon::parse($schedule->tanggal)->format('d/m/Y') }} | 
                                            {{ $schedule->waktu_mulai }} - {{ $schedule->waktu_selesai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan / Keluhan (Opsional):</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Deskripsikan keluhan atau catatan untuk dokter...">{{ old('catatan') }}</textarea>
                                <small class="form-text text-muted">Maksimal 500 karakter</small>
                            </div>

                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Informasi Penting:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Anda hanya dapat memiliki 1 antrian aktif</strong> dalam waktu bersamaan</li>
                                        <li>Setelah booking berhasil, Anda akan otomatis masuk ke antrian</li>
                                        <li>Nomor antrian akan diberikan berdasarkan urutan booking</li>
                                        <li>Anda akan diarahkan ke halaman antrian hari ini untuk memantau status</li>
                                        <li>Pastikan hadir tepat waktu sesuai dengan urutan antrian</li>
                                    </ul>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-calendar-plus"></i> Booking Sekarang
                            </button>
                        </form>
                    @elseif($schedules->count() == 0)
                        <div class="alert alert-warning">
                            <h5>Tidak ada jadwal tersedia</h5>
                            <p>Saat ini tidak ada jadwal dokter yang tersedia untuk booking.</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <h6>Menu Lainnya:</h6>
                        <a href="{{ route('user.queue.today') }}" class="btn btn-warning">
                            <i class="fas fa-clock"></i> Antrian Hari Ini
                        </a>
                        <a href="{{ route('user.booking.history') }}" class="btn btn-secondary">
                            <i class="fas fa-history"></i> Riwayat Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
