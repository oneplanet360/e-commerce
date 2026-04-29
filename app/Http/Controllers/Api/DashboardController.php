<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $brands = Brand::count();
        $categories = Category::count();
        $reviews = Review::count();

        $latestProducts = Product::latest()->take(10)->get([
            'id',
            'name',
            'price',
            'stock',
            'status',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'metrics' => [
                    'products' => $products,
                    'brands' => $brands,
                    'categories' => $categories,
                    'reviews' => $reviews,
                ],
                'latest_products' => $latestProducts,
            ],
        ]);
    }
}
