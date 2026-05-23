@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 shadow-lg">
            <div class="text-center mb-4">
                <i class="bi bi-box-arrow-in-right text-primary" style="font-size: 3rem;"></i>
                <h3 class="fw-bold">Login</h3>
                <p class="text-muted">Welcome back to our clinic</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill mb-3">Login</button>
                
                <div class="text-center">
                    <span>Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold">Register here</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
