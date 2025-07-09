@extends('layouts.app')
@section('title', 'Register')
@section('content')
<h1>Register Akun</h1>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Daftar sebagai</label>
        <select name="role" class="form-control" required>
            <option value="user">Pasien</option>
            <option value="admin">Admin</option> {{-- opsional: bisa disembunyikan --}}
        </select>
    </div>

    <button class="btn btn-success">Daftar</button>
</form>
@endsection
