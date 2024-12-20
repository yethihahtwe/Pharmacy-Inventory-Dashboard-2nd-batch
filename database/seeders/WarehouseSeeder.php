<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = ['Warehouse One', 'Warehouse Two', 'Warehouse Three', 'Warehouse Four', 'Warehouse Five'];

        foreach ($warehouses as $warehouse) {
            Warehouse::firstOrCreate(['name' => $warehouse]);
        }
    }
}
