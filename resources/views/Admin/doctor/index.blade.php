@extends('layouts.app')
@section('title', 'Manajemen Dokter')
@section('content')
    <h1>Daftar Dokter</h1>
    <a href="{{ route('admin.doctor.create') }}" class="btn btn-primary mb-2">+ Tambah Dokter</a>
    <table class="table">
        <thead><tr><th>Nama</th><th>Spesialis</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach ($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->specialist }}</td>
                    <td>
                        <a href="{{ route('admin.doctor.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.doctor.destroy', $doctor->id) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
