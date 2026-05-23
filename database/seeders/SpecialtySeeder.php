<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular diseases'],
            ['name' => 'Dermatology', 'description' => 'Skin, hair and nail disorders'],
            ['name' => 'Pediatrics', 'description' => 'Medical care for infants, children and adolescents'],
            ['name' => 'Orthopedics', 'description' => 'Musculoskeletal system and bone disorders'],
            ['name' => 'Dentistry', 'description' => 'Oral health and dental surgery'],
            ['name' => 'Neurology', 'description' => 'Disorders of the nervous system'],
            ['name' => 'Ophthalmology', 'description' => 'Eye and vision care'],
        ];
        foreach($specialties as $specialty){
            Specialty::create($specialty);
        }
    }
}
