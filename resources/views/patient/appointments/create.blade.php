@extends('layouts.main')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- doctor Summary Card -->
            <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                <div class="row g-0 align-items-center p-3">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <div class="col-md-10">
                        <div class="card-body py-2">
                            <h4 class="fw-bold mb-1">Booking with Dr. {{ $doctor->user->name }}</h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-info text-dark me-2">{{ $doctor->specialty->name }}</span>
                                <i class="bi bi-clock me-1"></i> Session: {{ $doctor->appointment_duration }} mins
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form Card -->
            <div class="card shadow-lg border-0 p-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-center"><i class="bi bi-calendar-event me-2 text-primary"></i>Select Date
                        and Time</h5>

                    <form action="{{ url('appointments/create') }}" method="POST">
                        @csrf

                        <!-- Hidden doctor ID -->
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <div class="row mb-4">
                            <!-- Appointment Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Appointment Date</label>
                                <input type="date" name="appointment_date" id="appointment_date"
                                    class="form-control form-control-lg" min="{{ date('Y-m-d') }}"
                                    value="{{ $selectedDate }}"
                                    onchange="window.location.href = '{{ url()->current() }}?date=' + this.value">

                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Appointment Time -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Preferred Time</label>
                                <select name="appointment_time"
                                    class="form-select form-select-lg @error('appointment_time') is-invalid @enderror"
                                    required>
                                    <option value="" selected disabled>Choose a time...</option>

                                    @php
                                        // تحديد وقت البداية والنهاية (مثلاً من 9 صباحاً لـ 5 مساءً)
                                        $start = Carbon\Carbon::createFromTime(9, 0);
                                        $end = Carbon\Carbon::createFromTime(17, 0);

                                        // جلب مدة الجلسة الخاصة بهذا الطبيب من قاعدة البيانات
                                        $duration = $doctor->appointment_duration;
                                    @endphp

                                    @while ($start->lt($end))
                                        @php
                                            $currentTime = $start->format('H:i');
                                            $isBooked = in_array($currentTime, $bookedSlots);
                                        @endphp

                                        {{-- نعرض الوقت فقط إذا لم يكن محجوزاً --}}
                                        @if (!$isBooked)
                                            <option value="{{ $currentTime }}">
                                                {{ $start->format('h:i A') }}
                                            </option>
                                        @endif

                                        @php $start->addMinutes($duration); @endphp
                                    @endwhile

                                </select>

                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Patient Notes -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Notes for the Doctor (Optional)</label>
                            <textarea name="patient_notes" class="form-control" rows="3" placeholder="Tell the doctor about your symptoms..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url('/patient/doctors') }}"
                                class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                Confirm Booking <i class="bi bi-check2-circle ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
