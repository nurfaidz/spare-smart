<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\SparePart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::get();

        $products = [
            [
                'code' => 'SP-001',
                'name' => 'Shock Breaker Yamaha Mio',
                'brand_id' => $brands->where('name', 'YAMAHA')->first()->id,
                'current_price' => 20000000,
                'stock' => 10,
            ],
            [
                'code' => 'SP-002',
                'name' => 'Ban Luar Honda Beat',
                'brand_id' => $brands->where('name', 'HONDA')->first()->id,
                'current_price' => 18000000,
                'stock' => 5,
            ],
            [
                'code' => 'SP-003',
                'name' => 'Kampas Rem Suzuki Satria',
                'brand_id' => $brands->where('name', 'SUZUKI')->first()->id,
                'current_price' => 22000000,
                'stock' => 3,
            ],
            [
                'code' => 'SP-004',
                'name' => 'Kampas Kopling Kawasaki Ninja',
                'brand_id' => $brands->where('name', 'KAWASAKI')->first()->id,
                'current_price' => 50000000,
                'stock' => 2,
            ],
            [
                'code' => 'SP-005',
                'name' => 'Kampas Kopling TVS Apache',
                'brand_id' => $brands->where('name', 'TVS')->first()->id,
                'current_price' => 45000000,
                'stock' => 1,
            ],
        ];

        foreach ($products as $product) {
            SparePart::create($product);
        }
    }
}
