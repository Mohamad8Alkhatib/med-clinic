<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DoctorManagerController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        $validated = request()->validate([
            'date' => ['nullable', 'date'],
        ]);
        $date = $validated['date'] ?? now()->toDateString();

        $todayAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->with(['patient'])
            ->get();

        $allAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->with(['patient'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $stats = [
            'today_count' => $todayAppointments->count(),
            'pending_count' => $allAppointments->where('status', 'pending')->count(),
            'total_patients' => $allAppointments->pluck('patient_id')->unique()->count(),
        ];

        return view('doctor.dashboard', compact('todayAppointments', 'allAppointments', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['cancelled', 'confirmed', 'completed'])],
            'doctor_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $doctorId = \auth()->user()->doctor->id;
        $appointment = Appointment::query()
            ->whereKey($id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();
        $status = $validated['status'];
        $currentStatus = $appointment->status;

        if ($currentStatus == 'cancelled' || $currentStatus == 'completed')
            return back()->with('error', 'Cannot edit appointment status');

        if ($currentStatus == $status) {
            return back()->with('error', 'Status is already the same');
        }

        switch ($currentStatus) {
            case 'pending':
                if ($status == 'cancelled') {
                    $appointment->update([
                        'status' => 'cancelled',
                        'cancelled_by' => \auth()->id(),
                        'cancelled_at' => now(),
                    ]);
                } elseif ($status == 'confirmed') {
                    $appointment->update([
                        'status' => 'confirmed',
                        'cancelled_by' => null,
                        'cancelled_at' => null,
                    ]);
                }
                else {
                    return back()->with('error', 'Invalid status transition');
                }
                break;
            case 'confirmed':
                if ($status == 'completed') {
                    if (empty($validated['doctor_notes'])) {
                        return back()->with('error', 'Doctor notes are required when completing an appointment.');
                    }

                    $appointment->update([
                        'status' => 'completed',
                        'doctor_notes' => $validated['doctor_notes'],
                    ]);
                }
                elseif ($status == 'cancelled') {
                    $appointment->update([
                        'status' => 'cancelled',
                        'cancelled_by' => \auth()->id(),
                        'cancelled_at' => now(),
                    ]);
                }
                else {
                    return back()->with('error', 'Invalid status transition');
                }
                break;
        }

        return back()->with('message', 'Appointment status updated successfully');
    }
}
