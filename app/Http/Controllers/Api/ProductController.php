<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with images.
     */
    public function index(Request $request)
    {
        $query = Product::with(['gallery', 'category', 'variants'])->where('status', true);

        // Filter by category ID or Slug
        if ($request->has('category')) {
            $category = $request->category;
            $query->whereHas('category', function($q) use ($category) {
                $q->where('id', $category)
                  ->orWhere('slug', $category);
            });
        }

        // Filter by specific popular status if requested
        if ($request->has('is_popular')) {
            $query->where('is_popular', $request->boolean('is_popular'));
        }

        $products = $query->latest()->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products
        ]);
    }

    /**
     * Display the specified product with all details and gallery images.
     */
    public function show($identifier)
    {
        $product = Product::with(['gallery', 'variants', 'category'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}
