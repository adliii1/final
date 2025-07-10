@extends('layouts.app')
@section('title', 'Register')
@section('content')

    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #ffffff);
        }

        .register-card {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .register-card h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
            font-weight: 600;
        }
    </style>

    <div class="register-card">
        <h1>Registrasi Akun</h1>

        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama lengkap" required>
                <label for="name">Nama Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Alamat email" required>
                <label for="email">Alamat Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Ulangi password" required>
                <label for="password_confirmation">Konfirmasi Password</label>
            </div>

            <div class="form-floating mb-3">
                <select name="role" class="form-select" id="role" required>
                    <option value="user">Pasien</option>
                    <option value="admin">Admin</option>
                </select>
                <label for="role">Daftar Sebagai</label>
            </div>

            <div class="d-grid">
                <button class="btn btn-primary">Daftar</button>
            </div>

            <div class="text-center mt-3">
                <small>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></small>
            </div>
        </form>
    </div>
@endsection