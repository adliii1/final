@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Antrian Hari Ini') }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h4>
                    </div>

                    <div class="card-body">
                        <!-- Filter Doctor -->
                        <form method="GET" action="{{ route('user.queue.today') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="doctor_id">Filter Dokter:</label>
                                    <select name="doctor_id" id="doctor_id" class="form-control"
                                        onchange="this.form.submit()">
                                        <option value="">Semua Dokter</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ $doctorId == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }} - {{ $doctor->specialist }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="button" class="btn btn-secondary" onclick="refreshQueue()">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if($queues->count() > 0)
                            <!-- Summary -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $queues->where('status', 'waiting')->count() }}</h5>
                                            <p>Menunggu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $queues->where('status', 'in_progress')->count() }}</h5>
                                            <p>Sedang Dilayani</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Queue Display -->
                            @php
                                $currentQueue = $queues->where('status', 'in_progress')->first();
                                $nextQueue = $queues->where('status', 'waiting')->first();
                            @endphp

                            @if($currentQueue)
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-user-clock"></i> Sedang Dilayani:</h5>
                                    <p><strong>No. {{ $currentQueue->queue_number }}</strong> -
                                        {{ $currentQueue->booking->user->name }}
                                    </p>
                                    <small>Dokter: {{ $currentQueue->booking->schedule->doctor->name }}</small>
                                </div>
                            @endif

                            @if($nextQueue)
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-hourglass-half"></i> Antrian Selanjutnya:</h5>
                                    <p><strong>No. {{ $nextQueue->queue_number }}</strong> - {{ $nextQueue->booking->user->name }}
                                    </p>
                                    <small>Dokter: {{ $nextQueue->booking->schedule->doctor->name }}</small>
                                </div>
                            @endif

                            <!-- Queue List -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Antrian</th>
                                            <th>Nama Pasien</th>
                                            <th>Dokter</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="queueTable">
                                        @foreach($queues as $queue)
                                            <tr class="
                                                                                @if($queue->status === 'waiting') table-warning
                                                                                @elseif($queue->status === 'in_progress') table-info
                                                                                @elseif($queue->status === 'completed') table-success
                                                                                @elseif($queue->status === 'cancelled') table-danger
                                                                                @endif
                                                                            ">
                                                <td>
                                                    <span class="badge badge-lg 
                                                                                        @if($queue->status === 'waiting') badge-warning
                                                                                        @elseif($queue->status === 'in_progress') badge-info
                                                                                        @elseif($queue->status === 'completed') badge-success
                                                                                        @elseif($queue->status === 'cancelled') badge-danger
                                                                                        @endif
                                                                                    ">
                                                        {{ $queue->queue_number }}
                                                    </span>
                                                </td>
                                                <td>{{ $queue->booking->user->name }}</td>
                                                <td>{{ $queue->booking->schedule->doctor->name }}</td>
                                                <td>
                                                    @if($queue->status === 'waiting')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($queue->status === 'in_progress')
                                                        <span class="badge badge-info">Sedang Dilayani</span>
                                                    @elseif($queue->status === 'completed')
                                                        <span class="badge badge-success">Selesai</span>
                                                    @elseif($queue->status === 'cancelled')
                                                        <span class="badge badge-danger">Dibatalkan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5>Tidak ada antrian hari ini</h5>
                                <p>Belum ada antrian untuk hari ini{{ $doctorId ? ' untuk dokter yang dipilih' : '' }}.</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('user.booking.index') }}" class="btn btn-success">Buat Booking Baru</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshQueue() {
            window.location.reload();
        }

        // Auto refresh every 30 seconds
        setInterval(function () {
            refreshQueue();
        }, 30000);
    </script>
@endsection