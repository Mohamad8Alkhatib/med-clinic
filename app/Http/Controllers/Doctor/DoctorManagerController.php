<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

class DoctorManagerController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->doctor->id;
        $date = request('date', now()->toDateString());

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
        $appointment = Appointment::findOrFail($id);
        $doctorId = \auth()->user()->doctor->id;
        $allowed = ['cancelled', 'confirmed', 'completed'];
        $status = $request->input('status');
        $currentStatus = $appointment->status;

        if ($appointment->doctor_id != $doctorId) {
            return back()->with('error', 'Unauthorized to edit');
        }
        if (!in_array($status, $allowed))
            return back()->with('error', 'Invalid value');

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
                    ]);
                } elseif ($status == 'confirmed') {
                    $appointment->update([
                        'status' => 'confirmed',
                    ]);
                }
                else {
                    return back()->with('error', 'Invalid status transition');
                }
                break;
            case 'confirmed':
                if ($status == 'completed') {
                    $appointment->update([
                        'status' => 'completed',
                        'doctor_notes' => $request->input('doctor_notes'),
                    ]);
                }
                elseif ($status == 'cancelled') {
                    $appointment->update([
                        'status' => 'cancelled',
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
