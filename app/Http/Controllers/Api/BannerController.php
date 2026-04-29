<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketingBanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the active marketing banners.
     */
    public function index(Request $request)
    {
        $query = MarketingBanner::where('is_active', true)
                                  ->orderBy('sort_order', 'asc');

        // Apply Position filter if provided
        if ($request->has('position')) {
            $query->where('position', $request->position);
        }

        $banners = $query->get();

        // If they filter by position, just return the flat array natively.
        // Otherwise, group them by position.
        
        if ($request->has('position')) {
            $data = $banners;
        } else {
            $data = $banners->groupBy('position');
        }

        return response()->json([
            'success' => true,
            'count' => $banners->count(),
            'data' => $data
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified marketing banner.
     */
    public function show($id)
    {
        $banner = MarketingBanner::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found'
            ], 404, [], JSON_PRETTY_PRINT);
        }

        // Image URL is handled by the model accessor image_url

        return response()->json([
            'success' => true,
            'data' => $banner
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
