@extends('layouts.app')
@section('title', 'Jadwal Dokter')
@section('content')
    <h1>Jadwal Praktik</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary mb-2">+ Tambah Jadwal</a>
    <table class="table">
        <thead>
            <tr>
                <th>Dokter</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->doctor->name }}</td>
                    <td>{{ $schedule->tanggal }}</td>
                    <td>{{ $schedule->waktu_mulai }} - {{ $schedule->waktu_selesai }}</td>
                    <td>
                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection