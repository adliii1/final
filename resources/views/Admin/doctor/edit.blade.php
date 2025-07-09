@extends('layouts.app')
@section('title', 'Edit Dokter')
@section('content')
<h1>Edit Dokter</h1>
<form method="POST" action="{{ route('admin.doctor.update', $doctor->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" value="{{ $doctor->name }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Spesialis</label>
        <input type="text" name="specialist" value="{{ $doctor->specialist }}" class="form-control" required>
    </div>
    <button class="btn btn-primary">Update</button>
</form>
@endsection
