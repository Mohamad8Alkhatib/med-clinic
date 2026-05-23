@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h2 class="fw-bold mb-4">System Reports</h2>

        <div class="row g-4">
            <!-- Appointment Reports Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body text-center p-4">
                        <div class="icon-box bg-primary-soft text-primary rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Appointments Reports</h5>
                        <p class="text-muted small">Detailed analysis of bookings, status changes, and schedules.</p>
                        <div class="d-flex justify-content-between mb-3 px-3">
                            <span class="badge bg-light text-dark">Total: {{ $stats['total_appointments'] }}</span>
                            <span class="badge bg-success-soft text-success">Confirmed:
                                {{ $stats['confirmed_appointments'] }}</span>
                        </div>
                        <a href="{{ url('/admin/reports/appointments') }}"
                            class="btn btn-outline-primary rounded-pill w-100">View Details</a>
                    </div>
                </div>
            </div>

            <!-- doctor Performance Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body text-center p-4">
                        <div class="icon-box bg-info-soft text-info rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Doctor Performance</h5>
                        <p class="text-muted small">Monitor doctor activities, most requested doctors, and bios.</p>
                        <div class="mb-3">
                            <span class="badge bg-light text-dark">Active Doctors:
                                {{ $stats['active_doctors_count'] }}</span>
                            {{-- اختياري: يمكنك عرض الإجمالي بجانبه بشكل صغير --}}
                            <small class="text-muted">Out of {{ $stats['doctors_count'] }} total</small>
                        </div>
                        <a href="{{ url('/admin/reports/doctors') }}" class="btn btn-outline-info rounded-pill w-100">View
                            Details</a>
                    </div>
                </div>
            </div>

            <!-- System Analytics Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <div class="card-body text-center p-4">
                        <div class="icon-box bg-danger-soft text-danger rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-graph-up-arrow fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Cancellations & Issues</h5>
                        <p class="text-muted small">Analyze why appointments are being cancelled and system health.</p>
                        <div class="mb-3">
                            <span class="badge bg-danger-soft text-danger">Cancelled:
                                {{ $stats['cancelled_appointments'] }}</span>
                        </div>
                        <a href="{{ url('/admin/reports/cancellations') }}"
                            class="btn btn-outline-danger rounded-pill w-100">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* لمسة جمالية لتأثير التمرير على البطاقات */
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .1) !important;
            transition: all 0.3s ease;
        }

        .bg-primary-soft {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-info-soft {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
@endsection
