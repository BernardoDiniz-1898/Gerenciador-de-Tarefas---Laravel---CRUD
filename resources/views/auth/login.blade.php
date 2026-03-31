@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-3 mt-5">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-4">Entrar</h3>
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Entrar</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none">Não tem conta? Cadastre-se</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection