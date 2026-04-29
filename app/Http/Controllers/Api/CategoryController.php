<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::where('status', true)->get();

        return response()->json([
            'success' => true,
            'count' => $categories->count(),
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show($identifier)
    {
        $category = Category::where(function($query) use ($identifier) {
            $query->where('id', $identifier)
                  ->orWhere('slug', $identifier);
        })->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
