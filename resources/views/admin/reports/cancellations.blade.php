@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- رأس الصفحة --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-danger">Cancellations Report</h2>
            <a href="{{ url('/admin/reports') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Hub
            </a>
        </div>

        {{-- بطاقة الإحصائيات البسيطة --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-danger text-white rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="display-6 me-3"><i class="bi bi-exclamation-octagon"></i></div>
                            <div>
                                <h6 class="mb-0">Total Cancellations</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_cancelled'] }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول المواعيد الملغاة --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Specialty</th>
                                <th class="pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cancelledAppointments as $appointment)
                                <tr>
                                    <td class="ps-4">
                                        {{-- عرض التاريخ بشكل أنيق (مثلاً: 12 Mar 2024) --}}
                                        <div class="fw-bold">
                                            {{ $appointment->appointment_date->format('d M Y') }}
                                        </div>

                                        {{-- عرض الوقت بشكل أنيق بدون ثواني (مثلاً: 10:30 AM) --}}
                                        <div class="small text-muted">
                                            {{ $appointment->appointment_time->format('h:i A') }}
                                        </div>

                                    </td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $appointment->doctor->specialty->name }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-danger-soft text-danger rounded-pill px-3">
                                            Cancelled
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle d-block mb-2 fs-3"></i>
                                        No cancelled appointments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
@endsection
