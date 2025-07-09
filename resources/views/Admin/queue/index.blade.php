@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Antrian</h4>
                        <p class="mb-0 text-muted">Daftar semua antrian pasien</p>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($queues->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Antrian</th>
                                            <th>Pasien</th>
                                            <th>Dokter</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($queues as $queue)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $queue->queue_number }}
                                                    </span>
                                                </td>
                                                <td>{{ $queue->booking->user->name }}</td>
                                                <td>{{ $queue->booking->schedule->doctor->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($queue->queue_date)->format('d/m/Y') }}</td>
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

                            <!-- Pagination -->
                            @if(method_exists($queues, 'links'))
                                {{ $queues->links() }}
                            @endif
                        @else
                            <div class="alert alert-info text-center">
                                <h5>Tidak ada antrian</h5>
                                <p class="mb-0">Belum ada antrian yang terdaftar.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection