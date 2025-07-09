@extends('layouts.app')
@section('title', 'Tambah Jadwal Dokter')
@section('content')
    <h1>Tambah Jadwal</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.schedules.store') }}">
        @csrf
        <div class="mb-3">
            <label for="doctor_id" class="form-label">Dokter</label>
            <select name="doctor_id" id="doctor_id" class="form-control">
                <option value="">-- Pilih Dokter --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }} - {{ $doctor->specialist }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}">
        </div>
        <div class="mb-3">
            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
            <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}">
        </div>
        <div class="mb-3">
            <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
            <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control"
                value="{{ old('waktu_selesai') }}">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection