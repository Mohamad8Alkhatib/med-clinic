<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $doctorsCount = Doctor::count();
        $patientsCount = User::query()
            ->where('role', 'patient')
            ->count();
        $appointmentsCount = Appointment::count();
        $pendingCount = Appointment::query()
            ->where('status', 'pending')
            ->count();

        return view('admin.dashboard', compact('doctorsCount', 'patientsCount', 'appointmentsCount', 'pendingCount'));
    }

    public function reportsIndex()
    {
        $stats = [
            'total_appointments' => Appointment::count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
            'doctors_count' => Doctor::count(),
            'active_doctors_count' => Doctor::where('is_active', 1)->count(),
        ];
        return view('admin.reports.index', compact('stats'));
    }

    public function appointmentReport(Request $request)
    {
        //get relation for filter
        $doctors = Doctor::with('user')->get();

        $query = Appointment::with(['doctor.user', 'patient']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->where('appointment_date', $request->date);
        }
        //sort results latest first
        $appointments = $query->orderBy('appointment_date', 'desc')->get();

        return view('admin.reports.appointments', compact('appointments', 'doctors'));
    }

    public function doctorsReport()
    {
        $doctors = Doctor::query()
            ->with(['user', 'specialty'])
            ->withCount([
                'appointments as total_appointments',
                'appointments as completed_appointments' => function ($query) {
                    $query->where('status', 'completed');
                },
                'appointments as cancelled_appointments' => function ($query) {
                    $query->where('status', 'cancelled');
                },
                'appointments as pending_appointments' => function ($query) {
                    $query->where('status', 'pending');
                }
            ])
            ->get();

        return view('admin.reports.doctors', compact('doctors'));
    }

    public function cancellationReport()
    {
        $cancelledAppointments = Appointment::query()
            ->where('status', 'cancelled')
            ->with(['doctor.specialty', 'patient'])
            ->orderBy('cancelled_at', 'desc')
            ->get();

        $stats = [
            'total_cancelled' => $cancelledAppointments->count(),
        ];
        return view('admin.reports.cancellations', compact('cancelledAppointments', 'stats'));
    }
}