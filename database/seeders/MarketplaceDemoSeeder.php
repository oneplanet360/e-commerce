<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = collect(['Aura', 'Velora', 'Zenith'])->map(function ($name) {
            return Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                    'total_items' => 0,
                ]
            );
        });

        $products = Product::query()->take(5)->get();
        if ($products->isNotEmpty()) {
            foreach ($products as $index => $product) {
                $brand = $brands[$index % $brands->count()];
                $product->update(['brand_id' => $brand->id]);
            }
        }

        if ($products->isNotEmpty()) {
            Review::firstOrCreate(
                [
                    'product_id' => $products->first()->id,
                    'customer_name' => 'Demo User',
                ],
                [
                    'rating' => 5,
                    'status' => 'approved',
                    'review_text' => 'Excellent quality and packaging.',
                    'date_added' => now()->toDateString(),
                ]
            );
        }

        foreach ($brands as $brand) {
            $brand->update(['total_items' => Product::where('brand_id', $brand->id)->count()]);
        }
    }
}
