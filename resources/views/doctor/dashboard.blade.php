@extends('layouts.main')

@section('content')
    <div class="container-fluid py-4">
        {{-- ترحيب بالطبيب --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Welcome, Dr. {{ auth()->user()->name }}</h2>
                <p class="text-muted">Here's what's happening in your clinic today.</p>
            </div>
            <div class="text-end">
                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="date" name="date" class="form-control rounded-pill"
                           value="{{ request('date', now()->toDateString()) }}">
                    <button class="btn btn-primary rounded-pill px-3">View</button>
                </form>
            </div>
        </div>

        {{-- بطاقات الإحصائيات (Stats Cards) --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Today's Appointments</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['today_count'] }}</h2>
                            </div>
                            <div class="display-6 opacity-50"><i class="bi bi-calendar-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark-50 mb-1">Pending Requests</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['pending_count'] }}</h2>
                            </div>
                            <div class="display-6 opacity-50"><i class="bi bi-clock-history"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Patients</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_patients'] }}</h2>
                            </div>
                            <div class="display-6 opacity-50"><i class="bi bi-people"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول مواعيد اليوم --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark">Schedules</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Patient Name</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($todayAppointments as $appointment)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="avatar-sm bg-primary-soft text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold">
                                        {{ strtoupper(substr($appointment->patient->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $appointment->patient->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                            </td>
                            <td>
                            <span class="badge rounded-pill
                                {{ $appointment->status == 'pending' ? 'bg-warning text-dark' : ($appointment->status == 'confirmed' ? 'bg-success' : 'bg-info') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            </td>
                            <td class="text-center pe-4">
                                {{-- أزرار التحكم في الحالة --}}
                                @if($appointment->status == 'pending')
                                    <form action="{{ url('/doctor/appointments/'.$appointment->id.'/status') }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 me-1">Confirm
                                        </button>
                                    </form>
                                    <form action="{{ url('/doctor/appointments/'.$appointment->id.'/status') }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Cancel</button>
                                    </form>
                                @elseif($appointment->status == 'confirmed')
                                    <button class="btn btn-sm btn-primary rounded-pill px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#completeModal"
                                            data-bs-id="{{ $appointment->id }}">
                                        Mark as Completed
                                    </button>
                                @else
                                    <span class="text-muted small">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                No appointments scheduled for today.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal إكمال الموعد وكتابة الملاحظات --}}
    <div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="completeForm" method="POST" class="modal-content border-0 shadow rounded-4">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="completed">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Complete Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Please provide your medical notes or instructions for the
                        patient.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Doctor's Notes</label>
                        <textarea name="doctor_notes" class="form-control rounded-3" rows="4"
                                  placeholder="Diagnosis, prescription, or advice..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save & Complete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ربط الـ Modal بالـ Form لإرسال الـ ID الصحيح
        var completeModal = document.getElementById('completeModal');
        completeModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var appointmentId = button.getAttribute('data-bs-id');
            var form = document.getElementById('completeForm');
            form.action = '/doctor/appointments/' + appointmentId + '/status';
        });
    </script>

    <style>
        .bg-primary-soft {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
        }
    </style>
@endsection
