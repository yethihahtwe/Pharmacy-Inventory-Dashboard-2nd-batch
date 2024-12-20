<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Donor;
use App\Models\Warehouse;
use App\Models\PackageForm;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // all() method နဲ့ database ထဲက record အားလုံးဆွဲထုတ်မယ်
        $items = Item::all();
        $donors = Donor::all();
        $warehouses = Warehouse::all();
        $packageForms = PackageForm::all();

        foreach ($items as $item) {
            // property အတွဲတစ်စုံနဲ့ ဆေးပစ္စည်းတစ်မျိုးယာယီတည်ဆောက်မယ်
            $tempItem = [
                'item_id' => $item->id,
                'category_id' => $item->category_id,
                'package_form_id' => $packageForms->random()->id,
                'exp_date' => now()->addMonths(rand(6, 24)),
                'batch' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3)) . rand(1000, 9999),
                'donor_id' => $donors->random()->id,
                'source' => $warehouses->random()->id,
            ];

            // 'IN' transactions အရင်လုပ်မယ်
            $inCount = rand(1, 3);  // 'IN' transactions ၁ ကြိမ်မှ ၃ ကြိမ် ကျပန်းဖြစ်နိုင်
            for ($i = 0; $i < $inCount; $i++) {
                Transaction::create([
                    'date' => now()->subDays(rand(30, 365)),
                    'type' => 'IN',
                    'item_id' => $tempItem['item_id'],
                    'category_id' => $tempItem['category_id'],
                    'package_form_id' => $tempItem['package_form_id'],
                    'exp_date' => $tempItem['exp_date'],
                    'batch' => $tempItem['batch'],
                    'amount' => rand(100, 1000),
                    'donor_id' => $tempItem['donor_id'],
                    'source' => $tempItem['source'],
                    'destination' => null,
                    'remarks' => 'Stock in for ' . $item->name,
                ]);
            }

            // 'OUT' transactions ဆက်လုပ်မယ်
            $outCount = rand(2, 5);  // 'OUT' transactions ၂ ကြိမ်မှ ၅ ကြိမ်အတွင်း ကျပန်းဖြစ်နိုင်
            for ($i = 0; $i < $outCount; $i++) {
                Transaction::create([
                    'date' => now()->subDays(rand(1, 29)),  // 'IN' transactions တွေထက် နောက်ကျမှဖြစ်ဖို့လို
                    'type' => 'OUT',
                    'item_id' => $tempItem['item_id'],
                    'category_id' => $tempItem['category_id'],
                    'package_form_id' => $tempItem['package_form_id'],
                    'exp_date' => $tempItem['exp_date'],
                    'batch' => $tempItem['batch'],
                    'amount' => -rand(10, 50),  // 'OUT' မို့လို့ အနုတ်လက္ခဏာလို၊ 'IN' တုန်းက amount ထက်နည်းရမယ်
                    'donor_id' => $tempItem['donor_id'],
                    'source' => $tempItem['source'],
                    'destination' => $warehouses->where('id', '!=', $tempItem['source'])->random()->id,
                    'remarks' => 'Stock out for ' . $item->name,
                ]);
            }
        }
    }
}
