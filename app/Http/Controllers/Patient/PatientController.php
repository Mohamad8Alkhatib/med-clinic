<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Illuminate\Support\now;

class PatientController extends Controller
{
    public function index()
    {
        $doctors = Doctor::query()
            ->where('is_active', 1)
            ->whereHas('specialty')
            ->with(['user', 'specialty'])
            ->get();
        return view('patient.doctors.index', compact('doctors'));
    }

    public function createAppointment(Doctor $doctor, Request $request)
    {
        $selectedDate = $request->query('date', date('Y-m-d'));

        $bookedSlots = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', $selectedDate)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        return view('patient.appointments.create', compact('doctor', 'bookedSlots', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'patient_notes' => 'nullable|string'
        ]);

        $exists = Appointment::query()
            ->where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sorry, this time slot is already booked. Please choose another time.');
        }

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $validator['doctor_id'],
            'appointment_date' => $validator['appointment_date'],
            'appointment_time' => $validator['appointment_time'],
            'patient_notes' => $validator['patient_notes'],
            'status' => 'pending'
        ]);
        return redirect('patient/my-appointments')->with('success', 'Appointment booked successfully!');
    }

    public function myAppointments()
    {
        // $appointments = Appointment::where('patient_id', Auth::id())->get();
        $appointments = Appointment::query()
            ->where('patient_id', Auth::id())
            ->with(['doctor.user', 'doctor.specialty'])
            ->latest()
            ->get();

        return view('patient.appointments.index', compact('appointments'));
    }

    public function cancel($appointmentId)
    {
        $appointment = Appointment::query()
            ->where('id', $appointmentId)
            ->where('patient_id', Auth::id())
            ->first();

        if (!$appointment) {
            return back()->with('error', 'Appointment not found or unauthorized.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_by' => Auth::id(),
            'cancelled_at' => now()
        ]);

        return redirect('patient/my-appointments')->with('success', 'Appointment cancelled successfully');
    }
}
