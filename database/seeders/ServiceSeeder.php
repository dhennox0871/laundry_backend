<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\OrderItem::truncate();
        \App\Models\Order::truncate();
        \App\Models\Service::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $services = [
            // Paketan
            ['name' => 'Cuci Seterika (Paket 15)', 'price' => 35000, 'unit' => 'potong', 'pricing_model' => 'package', 'package_qty' => 15],
            ['name' => 'Cuci Kering (Paket 15)', 'price' => 25000, 'unit' => 'potong', 'pricing_model' => 'package', 'package_qty' => 15],
            ['name' => 'Cuci Saja (Paket 15)', 'price' => 20000, 'unit' => 'potong', 'pricing_model' => 'package', 'package_qty' => 15],
            ['name' => 'Seterika Saja (Paket 15)', 'price' => 20000, 'unit' => 'potong', 'pricing_model' => 'package', 'package_qty' => 15],

            // Kiloan
            ['name' => 'Cuci Seterika (Per Kg)', 'price' => 7000, 'unit' => 'kg', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Cuci Kering (Per Kg)', 'price' => 5000, 'unit' => 'kg', 'pricing_model' => 'item', 'package_qty' => null],

            // Satuan / Perbiji
            ['name' => 'Jas', 'price' => 25000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Jas Stelan', 'price' => 31000, 'unit' => 'stelan', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Kemeja / Hem', 'price' => 20000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Kaos / T.Shirt', 'price' => 19000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Celana Panjang', 'price' => 19000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Celana Pendek', 'price' => 18000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Bed Cover', 'price' => 40000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Selimut', 'price' => 28000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Sprei', 'price' => 22000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
            ['name' => 'Sarung Bantal/Guling', 'price' => 12000, 'unit' => 'potong', 'pricing_model' => 'item', 'package_qty' => null],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
