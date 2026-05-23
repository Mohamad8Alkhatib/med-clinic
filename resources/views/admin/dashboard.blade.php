@extends('layouts.main')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Admin Dashboard</h2>
        <p class="text-muted">Overview of your clinic's performance and management tools.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center h-100">
            <div class="card-body">
                <i class="bi bi-people text-primary fs-1"></i>
                <h3 class="fw-bold mt-2">{{ $doctorsCount }}</h3>
                <p class="text-muted mb-0">Total Doctors</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center h-100">
            <div class="card-body">
                <i class="bi bi-person-heart text-info fs-1"></i>
                <h3 class="fw-bold mt-2">{{ $patientsCount }}</h3>
                <p class="text-muted mb-0">Total Patients</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center h-100">
            <div class="card-body">
                <i class="bi bi-calendar-check text-success fs-1"></i>
                <h3 class="fw-bold mt-2">{{ $appointmentsCount }}</h3>
                <p class="text-muted mb-0">Total Appointments</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center h-100">
            <div class="card-body">
                <i class="bi bi-hourglass-split text-warning fs-1"></i>
                <h3 class="fw-bold mt-2">{{ $pendingCount }}</h3>
                <p class="text-muted mb-0">Pending Requests</p>
            </div>
        </div>
    </div>
</div>

<!-- Management Shortcuts -->
<div class="row">
    <div class="col-12">
        <h4 class="fw-bold mb-4">Quick Management</h4>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="bi bi-tags me-2 text-primary"></i>Specialties</h5>
                <p class="text-muted small">Add, edit, or remove medical specialties from the system.</p>
                <a href="{{ url('/admin/specialties') }}" class="btn btn-primary rounded-pill w-100">Manage Specialties</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Doctors</h5>
                <p class="text-muted small">Register new doctors and assign them to specialties.</p>
                <a href="{{ url('/admin/doctors') }}" class="btn btn-primary rounded-pill w-100">Manage Doctors</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>Reports</h5>
                <p class="text-muted small">View all appointments and filter them by date or status.</p>
                <a href="{{ url('admin/reports') }}" class="btn btn-primary rounded-pill w-100">View All Appointments</a>
            </div>
        </div>
    </div>
</div>
@endsection
