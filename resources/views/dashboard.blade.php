@extends('layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card p-5 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="lead text-muted mb-4">
                        You are currently logged in as a <span class="badge bg-primary px-3 rounded-pill">{{ ucfirst(auth()->user()->role) }}</span>.
                    </p>

                    <div class="mt-4">
                        @if(auth()->user()->role == 'patient')
                            <h5 class="fw-bold mb-3">Quick Actions</h5>
                            <div class="d-flex gap-3">
                                <a href="{{ url('/patient/doctors') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                                    <i class="bi bi-calendar-plus me-2"></i> Book an Appointment
                                </a>
                                <a href="{{ url('/patient/my-appointments') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                    <i class="bi bi-clock-history me-2"></i> My Appointments
                                </a>
                            </div>
                        @elseif(auth()->user()->role == 'doctor')
                            <h5 class="fw-bold mb-3">Doctor Panel</h5>
                            <a href="{{url('doctor/dashboard')}}" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-calendar3 me-2"></i> View Schedule
                            </a>
                        @else
                            <h5 class="fw-bold mb-3">Admin Panel</h5>
                            <a href="#" class="btn btn-dark btn-lg rounded-pill px-4">
                                <i class="bi bi-gear-fill me-2"></i> Manage System
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <i class="bi bi-speedometer2 text-primary" style="font-size: 8rem; opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
