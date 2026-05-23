@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Appointments Detailed Report</h2>
            <a href="{{ url('/admin/reports') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Back to Hub
            </a>
        </div>

        <!-- Filters Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">
                <form action="{{ url('/admin/reports/appointments') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Filter by Doctor</label>
                        <select name="doctor_id" class="form-select border-0 bg-light rounded-3">
                            <option value="">All Doctors</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Status</label>
                        <select name="status" class="form-select border-0 bg-light rounded-3">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Date</label>
                        <input type="date" name="date" class="form-control border-0 bg-light rounded-3"
                            value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 w-100">Filter Results</button>
                        <a href="{{ url('/admin/reports/appointments') }}"
                            class="btn btn-outline-secondary rounded-3">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $appointment->appointment_date->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $appointment->appointment_time->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                        <div class="small text-muted">{{ $appointment->patient->email }}</div>
                                    </td>
                                    <td>
                                        <div class="text-primary fw-bold">Dr. {{ $appointment->doctor->user->name }}</div>
                                        <div class="small text-muted">{{ $appointment->doctor->specialty->name }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass =
                                                [
                                                    'pending' => 'bg-warning text-dark',
                                                    'confirmed' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    'completed' => 'bg-info text-dark',
                                                ][$appointment->status] ?? 'bg-secondary';
                                        @endphp
                                        <span
                                            class="badge {{ $badgeClass }} rounded-pill px-3">{{ ucfirst($appointment->status) }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal"
                                            data-bs-target="#viewAppointmentModal"
                                            data-bs-patient="{{ $appointment->patient->name }}"
                                            data-bs-doctor="Dr. {{ $appointment->doctor->user->name }}"
                                            data-bs-date="{{ $appointment->appointment_date }}"
                                            data-bs-time="{{ $appointment->appointment_time }}"
                                            data-bs-status="{{ ucfirst($appointment->status) }}"
                                            data-bs-notes="{{ $appointment->notes ?? 'No notes provided' }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                                        No appointments found matching your filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Patient:</span>
                            <span class="fw-bold" id="modal-patient"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Doctor:</span>
                            <span class="fw-bold" id="modal-doctor"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Date & Time:</span>
                            <span class="fw-bold"><span id="modal-date"></span> at <span id="modal-time"></span></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Status:</span>
                            <span id="modal-status" class="badge rounded-pill"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <span class="text-muted d-block mb-2">Patient Notes:</span>
                            <div class="p-3 bg-light rounded-3 small" id="modal-notes"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewModal = document.getElementById('viewAppointmentModal');
            viewModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // الزر الذي فتح الـ Modal

                // جلب البيانات من الزر (تأكد من إضافة bs هنا)
                var patient = button.getAttribute('data-bs-patient');
                var doctor = button.getAttribute('data-bs-doctor');
                var date = button.getAttribute('data-bs-date');
                var time = button.getAttribute('data-bs-time');
                var status = button.getAttribute('data-bs-status');
                var notes = button.getAttribute('data-bs-notes');

                // تعبئة العناصر داخل الـ Modal (تأكد أن الـ IDs تطابق الموجود في الـ Modal)
                document.getElementById('modal-patient').textContent = patient;
                document.getElementById('modal-doctor').textContent = doctor;
                document.getElementById('modal-date').textContent = date;
                document.getElementById('modal-time').textContent = time;
                document.getElementById('modal-notes').textContent = notes;

                // تحديث الـ Badge الخاص بالحالة
                var statusBadge = document.getElementById('modal-status');
                statusBadge.textContent = status;

                // تغيير لون الـ Badge حسب الحالة
                statusBadge.className = 'badge rounded-pill ';
                if (status === 'Confirmed') statusBadge.classList.add('bg-success');
                else if (status === 'Cancelled') statusBadge.classList.add('bg-danger');
                else if (status === 'Completed') statusBadge.classList.add('bg-info', 'text-dark');
                else statusBadge.classList.add('bg-warning', 'text-dark');
            });
        });
    </script>
@endsection
