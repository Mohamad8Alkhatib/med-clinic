@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Doctors</h2>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
            data-bs-target="#addDoctorModal">
            <i class="bi bi-person-plus me-1"></i> Add New Doctor
        </button>
    </div>

    <!-- Doctors Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Doctor Name</th>
                            <th>Specialty</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-primary">{{ $doctor->user->name }}</div>
                                    <div class="small text-muted">{{ $doctor->user->email }}</div>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $doctor->specialty->name }}</span></td>
                                <td>{{ $doctor->appointment_duration }} mins</td>
                                <td>
                                    <form action="{{ url('/admin/doctors/' . $doctor->id . '/toggle-status') }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH') {{-- نستخدم PATCH لأننا نحدث جزءاً بسيطاً من البيانات --}}
                                        <button type="submit"
                                            class="btn btn-sm {{ $doctor->is_active ? 'btn-success' : 'btn-danger' }} rounded-pill px-3 shadow-sm"
                                            title="Click to change status">
                                            <i
                                                class="bi {{ $doctor->is_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                            {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                {{-- استبدل عمود الـ Actions بهذا الكود --}}
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#editDoctorModal"
                                            data-id="{{ $doctor->id }}" data-name="{{ $doctor->user->name }}"
                                            data-email="{{ $doctor->user->email }}"
                                            data-specialty="{{ $doctor->specialty_id }}"
                                            data-duration="{{ $doctor->appointment_duration }}"
                                            data-bio="{{ $doctor->bio }}">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ url('/admin/doctors/' . $doctor->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this doctor? Account access will be revoked.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No doctors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Adding doctor -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Register New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/admin/doctors') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Specialty</label>
                                <div class="input-group">
                                    <select name="specialty_id"
                                        class="form-select @error('specialty_id') is-invalid @enderror" required>
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- زر الإضافة السريع --}}
                                    <a href="{{ url('/admin/specialties') }}" class="btn btn-outline-primary"
                                        title="Add New Specialty">
                                        <i class="bi bi-plus-lg"></i>
                                    </a>
                                </div>
                                @error('specialty_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Duration (min)</label>
                                <input type="number" name="appointment_duration"
                                    class="form-control @error('appointment_duration') is-invalid @enderror"
                                    value="{{ old('appointment_duration', 30) }}" required>
                                @error('appointment_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bio (Optional)</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Register Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script to auto-open modal on validation errors --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addDoctorModal'));
                myModal.show();
            });
        </script>
    @endif
    <!-- Modal for Editing doctor -->
    <div class="modal fade" id="editDoctorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Edit Doctor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- لاحظ أننا سنقوم بتحديث الـ Action الخاص بالفورم عبر الـ JavaScript --}}
                <form action="" method="POST" id="editDoctorForm">
                    @csrf
                    @method('PUT') {{-- نستخدم PUT للتحديث --}}
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Specialty</label>
                                <select name="specialty_id" id="edit_specialty" class="form-select" required>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Duration (min)</label>
                                <input type="number" name="appointment_duration" id="edit_duration"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bio (Optional)</label>
                            <textarea name="bio" id="edit_bio" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="alert alert-info py-2 small border-0">
                            <i class="bi bi-info-circle me-1"></i> Leave password blank to keep the current one.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">New Password (Optional)</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Enter new password if you want to change it">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- كود الـ JavaScript لتعبئة البيانات تلقائياً --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editDoctorModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // الزر الذي تم الضغط عليه

                // جلب البيانات من الـ attributes الخاصة بالزر
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var email = button.getAttribute('data-email');
                var specialty = button.getAttribute('data-specialty');
                var duration = button.getAttribute('data-duration');
                var bio = button.getAttribute('data-bio');

                // تعبئة حقول الـ Modal
                editModal.querySelector('#edit_name').value = name;
                editModal.querySelector('#edit_email').value = email;
                editModal.querySelector('#edit_specialty').value = specialty;
                editModal.querySelector('#edit_duration').value = duration;
                editModal.querySelector('#edit_bio').value = bio;

                // تحديث رابط الـ Form ليكون /admin/doctors/{id}
                editModal.querySelector('#editDoctorForm').action = '{{ url('/admin/doctors') }}/' + id;
            });
        });
    </script>
@endsection
