<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            ['name' => 'Apex Whey Vanilla',    'type' => 'supplement', 'price' => 3500, 'stock_qty' => 50],
            ['name' => 'Protein Shaker Bottle', 'type' => 'equipment',  'price' => 800,  'stock_qty' => 30],
            ['name' => 'Locker Monthly',       'type' => 'locker',     'price' => 1500, 'stock_qty' => 20],
            ['name' => 'Energy Drink',         'type' => 'drink',      'price' => 150,  'stock_qty' => 100],
            ['name' => 'Creatine Monohydrate', 'type' => 'supplement', 'price' => 2800, 'stock_qty' => 40],
        ];

        foreach ($addons as $addon) {
            Addon::firstOrCreate(
                ['name' => $addon['name']],
                [
                    'type'      => $addon['type'],
                    'price'     => $addon['price'],
                    'stock_qty' => $addon['stock_qty'],
                    'is_active' => true,
                ]
            );
        }
    }
}
