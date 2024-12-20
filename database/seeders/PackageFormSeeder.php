<?php

namespace Database\Seeders;

use App\Models\PackageForm;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packageForms = ['Tablet', 'Capsule', 'Ampoule', 'Bottle', 'Tube', 'Sheet', 'Piece', 'Pair'];

        foreach ($packageForms as $packageForm) {
            PackageForm::firstOrCreate(['name' => $packageForm]);
        }
    }
}
