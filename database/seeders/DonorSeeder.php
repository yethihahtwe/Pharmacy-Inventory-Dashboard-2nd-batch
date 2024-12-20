<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donors = ['Donor One', 'Donor Two', 'Donor Three', 'Donor Four', 'Donor Five'];

        foreach ($donors as $donor) {
            Donor::firstOrCreate(['name' => $donor]);
        }
    }
}
