<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        if (!$doctor->is_active) {
            abort(404);
        }

        $selectedDate = $request->query('date', date('Y-m-d'));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = date('Y-m-d');
        }

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
            'doctor_id' => ['required', Rule::exists('doctors', 'id')->whereNull('deleted_at')->where('is_active', true)],
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'patient_notes' => 'nullable|string|max:1000'
        ]);

        $doctor = Doctor::query()->findOrFail($validator['doctor_id']);
        $selectedTime = Carbon::createFromFormat('H:i', $validator['appointment_time']);
        $start = Carbon::createFromTime(9, 0);
        $end = Carbon::createFromTime(17, 0);
        $availableSlots = [];

        while ($start->lt($end)) {
            $availableSlots[] = $start->format('H:i');
            $start->addMinutes($doctor->appointment_duration);
        }

        if (!in_array($selectedTime->format('H:i'), $availableSlots, true)) {
            throw ValidationException::withMessages([
                'appointment_time' => 'Invalid appointment time for this doctor.',
            ]);
        }

        DB::transaction(function () use ($validator) {
            Doctor::query()->whereKey($validator['doctor_id'])->lockForUpdate()->firstOrFail();

            $exists = Appointment::query()
                ->where('doctor_id', $validator['doctor_id'])
                ->where('appointment_date', $validator['appointment_date'])
                ->where('appointment_time', $validator['appointment_time'])
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'appointment_time' => 'Sorry, this time slot is already booked. Please choose another time.',
                ]);
            }

            Appointment::create([
                'patient_id' => Auth::id(),
                'doctor_id' => $validator['doctor_id'],
                'appointment_date' => $validator['appointment_date'],
                'appointment_time' => $validator['appointment_time'],
                'patient_notes' => $validator['patient_notes'] ?? null,
                'status' => 'pending'
            ]);
        });

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
