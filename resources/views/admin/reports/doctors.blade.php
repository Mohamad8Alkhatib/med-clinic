@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Doctor Performance Report</h2>
        <a href="{{ url('/admin/reports') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back to Hub
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Doctor Info</th>
                            <th class="text-center">Total Bookings</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Cancelled</th>
                            <th class="text-center">Success Rate</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary">Dr. {{ $doctor->user->name }}</div>
                                <div class="small text-muted">{{ $doctor->specialty->name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark fs-6 px-3">{{ $doctor->total_appointments }}</span>
                            </td>
                            <td class="text-center text-success fw-bold">
                                {{ $doctor->completed_appointments }}
                            </td>
                            <td class="text-center text-danger fw-bold">
                                {{ $doctor->cancelled_appointments }}
                            </td>
                            <td class="text-center">
                                @php
                                    $rate = $doctor->total_appointments > 0 
                                        ? round(($doctor->completed_appointments / $doctor->total_appointments) * 100) 
                                        : 0;
                                    $progressClass = $rate > 70 ? 'bg-success' : ($rate > 40 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="small mb-1">{{ $rate }}%</div>
                                <div class="progress" style="height: 5px; width: 80px; margin: 0 auto;">
                                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $rate }}%"></div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
