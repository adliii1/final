<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        Doctor::create(['name' => 'dr. Andi Pratama', 'specialist' => 'Umum']);
        Doctor::create(['name' => 'dr. Lestari Dewi', 'specialist' => 'Gigi']);
        Doctor::create(['name' => 'dr. Budi Wijaya', 'specialist' => 'Anak']);
    }
}
