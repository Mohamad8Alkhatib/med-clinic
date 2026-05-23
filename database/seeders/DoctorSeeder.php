<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. John Smith', 'email' => 'john@clinic.com'],
            ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah@clinic.com'],
            ['name' => 'Dr. Michael Brown', 'email' => 'michael@clinic.com'],
            ['name' => 'Dr. Emily Davis', 'email' => 'emily@clinic.com'],
            ['name' => 'Dr. David Wilson', 'email' => 'david@clinic.com'],
        ];
        foreach($doctors as $doctorData){
            $user = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('pass1234'),
                'role' => 'doctor'
            ]);
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => rand(1, 7),
                'bio' => fake()->paragraph(),
                'appointment_duration' => [30, 45, 60][rand(0, 2)],
            ]);
            for ($day=0; $day <= 4; $day++) { 
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00'
                ]);
            }
        }
    }
}
