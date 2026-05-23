<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Illuminate\Support\now;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = User::where('role', 'patient')->get();
        $times = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
        // past completed appointments
        for ($i=0; $i < 15; $i++) { 
            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status' => 'completed',
                'patient_notes' => fake()->sentence(),
                'doctor_notes' => fake()->sentence()
            ]);
        }
        // past cancelled appointments
        for ($i=0; $i < 8; $i++) { 
            $patient = $patients->random();
            $doctor = $doctors->random();
            $appointmentDate = now()->subDays(rand(1, 30));
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status' => 'cancelled',
                'patient_notes' => rand(0, 1) ? fake()->sentence() : null,
                'cancelled_by' => rand(0, 1) ? $patient->id : $doctor->user_id,
                'cancelled_at' => $appointmentDate->subDays(1),
            ]);
        }
        // nearly confirmed appointments
        for ($i = 0; $i < 10; $i++) {
            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => now()->addDays(rand(0, 3))->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status' => 'confirmed',
                'patient_notes' => rand(0, 1) ? fake()->sentence() : null,
            ]);
        }
        // futures pending appointments
        for ($i = 0; $i < 7; $i++) {
            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => now()->addDays(rand(4, 30))->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status' => 'pending',
                'patient_notes' => rand(0, 1) ? fake()->sentence() : null,
            ]);
        }
    }
}
