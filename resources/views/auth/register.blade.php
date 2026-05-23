@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-lg">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus text-primary" style="font-size: 3rem;"></i>
                <h3 class="fw-bold">Create Account</h3>
                <p class="text-muted">Join us for the best medical care experience</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <!-- Password -->
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill mb-3">Register Now</button>
                
                <div class="text-center">
                    <span>Already have an account? <a href="{{ route('login') }}" class="text-primary fw-bold">Login here</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
