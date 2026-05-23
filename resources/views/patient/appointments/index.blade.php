@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Appointments</h2>
        <a href="{{ url('/patient/doctors') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg"></i> Book New Appointment
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Doctor</th>
                            <th>Specialty</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle text-primary fs-4 me-2"></i>
                                        <span class="fw-bold">Dr. {{ $appointment->doctor->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $appointment->doctor->specialty->name }}</td>
                                <td>
                                    {{-- عرض التاريخ بشكل مقروء --}}
                                    <div class="fw-bold">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}
                                    </div>

                                    {{-- عرض وقت الموعد الذي اختاره المريض --}}
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $statusClass =
                                            [
                                                'pending' => 'bg-warning text-dark',
                                                'confirmed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                'completed' => 'bg-info text-dark',
                                            ][$appointment->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} rounded-pill px-3">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if ($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                        <form action="{{ url('/cancelAppointment/' . $appointment->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-x-circle me-1"></i> Cancel
                                            </button>
                                        </form>
                                    @endif
                                    <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal"
                                        data-bs-target="#viewAppointmentModal" {{-- يجب أن يطابق ID المودال --}}
                                        data-bs-doctor="Dr. {{ $appointment->doctor->user->name }}"
                                        data-bs-specialty="{{ $appointment->doctor->specialty->name }}"
                                        data-bs-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}"
                                        data-bs-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}"
                                        data-bs-status="{{ ucfirst($appointment->status) }}"
                                        data-bs-doctor-notes="{{ $appointment->doctor_notes ?? 'No notes from doctor yet.' }}"
                                        data-bs-patient-notes="{{ $appointment->notes ?? 'No notes provided.' }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x d-block mb-3" style="font-size: 3rem;"></i>
                                    No appointments found. Start by booking one!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal لعرض تفاصيل الموعد للمريض -->
    <div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="viewAppointmentModalLabel">
                        <i class="bi bi-calendar-check me-2"></i>Appointment Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1" id="modal-doctor"></h4>
                        <span class="badge bg-info-soft text-info rounded-pill px-3" id="modal-specialty"></span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6 text-center p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Date</small>
                            <span class="fw-bold" id="modal-date"></span>
                        </div>
                        <div class="col-6 text-center p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Time</small>
                            <span class="fw-bold" id="modal-time"></span>
                        </div>
                    </div>

                    <div class="mb-4 text-center">
                        <small class="text-muted d-block mb-2">Current Status</small>
                        <span id="modal-status" class="badge rounded-pill px-4 py-2 fs-6"></span>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small mb-2 text-uppercase">Doctor's Notes:</label>
                        <div class="p-3 bg-success-soft border border-success border-opacity-10 rounded-3"
                            id="modal-doctor-notes"></div>
                    </div>

                    <div class="mb-0">
                        <label class="fw-bold text-muted small mb-2 text-uppercase">Your Notes:</label>
                        <div class="p-3 bg-light rounded-3 small text-muted" id="modal-patient-notes"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-pill px-5"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-info-soft {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.08);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewModal = document.getElementById('viewAppointmentModal');

            if (viewModal) {
                viewModal.addEventListener('show.bs.modal', function(event) {
                    // الزر الذي تم الضغط عليه
                    var button = event.relatedTarget;

                    // استخراج البيانات من الـ attributes (data-bs-*)
                    var doctor = button.getAttribute('data-bs-doctor');
                    var specialty = button.getAttribute('data-bs-specialty');
                    var date = button.getAttribute('data-bs-date');
                    var time = button.getAttribute('data-bs-time');
                    var status = button.getAttribute('data-bs-status');
                    var docNotes = button.getAttribute('data-bs-doctor-notes');
                    var patNotes = button.getAttribute('data-bs-patient-notes');

                    // وضع البيانات في أماكنها داخل الـ Modal
                    document.getElementById('modal-doctor').textContent = doctor;
                    document.getElementById('modal-specialty').textContent = specialty;
                    document.getElementById('modal-date').textContent = date;
                    document.getElementById('modal-time').textContent = time;
                    document.getElementById('modal-doctor-notes').textContent = docNotes;
                    document.getElementById('modal-patient-notes').textContent = patNotes;

                    // تنسيق الـ Badge الخاص بالحالة
                    var statusBadge = document.getElementById('modal-status');
                    statusBadge.textContent = status;
                    statusBadge.className = 'badge rounded-pill px-4 py-2 fs-6 ';

                    // تغيير لون الحالة حسب النص
                    if (status === 'Confirmed') statusBadge.classList.add('bg-success');
                    else if (status === 'Cancelled') statusBadge.classList.add('bg-danger');
                    else if (status === 'Completed') statusBadge.classList.add('bg-primary');
                    else statusBadge.classList.add('bg-warning', 'text-dark');
                });
            }
        });
    </script>
@endsection
