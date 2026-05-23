@extends('layouts.main')

@section('content')
<div class="row">
    <!-- Form to Add New Specialty -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <h4 class="fw-bold mb-4 text-primary"><i class="bi bi-plus-circle me-2"></i>Add Specialty</h4>
            <form action="{{ url('/admin/specialties') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Specialty Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Cardiology" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Brief details about this specialty...">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                    Save Specialty <i class="bi bi-save ms-1"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Table of Existing Specialties -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Current Specialties</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Description</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specialties as $specialty)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">{{ $specialty->name }}</td>
                                <td class="text-muted small">{{ Str::limit($specialty->description, 50) }}</td>
                                <td class="text-end pe-4">
                                    <form action="{{ url('/admin/specialties/' . $specialty->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this specialty?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">No specialties added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
