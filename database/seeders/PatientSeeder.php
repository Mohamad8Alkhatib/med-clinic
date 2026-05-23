<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\PatientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(PatientFactory::class)->count(15)->create();
    }
}
