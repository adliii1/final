@extends('layouts.app')
@section('title', 'Login')
@section('content')

    <style>
        .login-card {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .login-card h1 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
    </style>

    <div class="login-card">
        <h1 class="text-center">Login</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password"
                    required>
                <label for="password">Password</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></small>
        </div>
    </div>
@endsection