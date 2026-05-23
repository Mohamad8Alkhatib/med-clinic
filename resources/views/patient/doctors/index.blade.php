@extends('layouts.main' )

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center">
        <h2 class="fw-bold">Choose Your Doctor</h2>
        <p class="text-muted">Access to qualified and experienced medical professionals</p>
    </div>
</div>

<div class="row g-4">
    @foreach($doctors as $doctor)
    <div class="col-md-4">
        <div class="card h-100 p-3 text-center">
            <div class="card-body">
                <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
                <h4 class="card-title mt-3 fw-bold">Dr. {{ $doctor->user->name }}</h4>
                <span class="badge bg-info text-dark mb-3">{{ $doctor->specialty->name }}</span>
                <p class="card-text text-muted small">
                    {{ Str::limit($doctor->bio, 100) }}
                </p>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-primary fw-bold">{{ $doctor->appointment_duration }} mins</span>
                    <a href="{{ url('/appointments/create/' . $doctor->id) }}" class="btn btn-primary rounded-pill px-4">Book Now</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
