<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 0; $i < 10; $i++) {  // category တစ်ခုမှာ ဆေး (၁၀) မျိုးဖန်တီးမယ်
                Item::create([
                    'name' => $this->generateMedicineName(),
                    'category_id' => $category->id,
                ]);
            }
        }
    }

    private function generateMedicineName(): string
    {
        $prefixes = ['Neo', 'Oxy', 'Phen', 'Hydro', 'Meth', 'Dex', 'Levo'];
        $roots = ['cort', 'derm', 'cillin', 'cycline', 'dryl', 'zole', 'prin'];
        $suffixes = ['ol', 'in', 'ate', 'ide', 'one', 'ine', 'il'];

        $name = $prefixes[array_rand($prefixes)] .
            $roots[array_rand($roots)] .
            $suffixes[array_rand($suffixes)];

        return ucfirst(strtolower($name));
    }
}
