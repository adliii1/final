@extends('layouts.app')
@section('title', 'Tambah Dokter')
@section('content')
<h1>Tambah Dokter</h1>
<form method="POST" action="{{ route('admin.doctor.store') }}">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Spesialis</label>
        <input type="text" name="specialist" class="form-control" required>
    </div>
    <button class="btn btn-success">Simpan</button>
</form>
@endsection
