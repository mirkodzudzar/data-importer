<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $productData = [];

        foreach(range(1100,1200) as $index) {
            $sku = 'ASDF-'.$index;
            $productData[] = $this->prepareProductData($sku, $now);
        }

        foreach(range(12300,12400) as $index) {
            $sku = 'N'.$index.'-99';
            $productData[] = $this->prepareProductData($sku, $now);
        }

        DB::table("products")->insert($productData);
    }

    private function prepareProductData(string $sku, $now): array
    {
        return [
            'sku' => $sku,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
