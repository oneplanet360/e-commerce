<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Product::count();
        $brandsCount = Brand::count();
        $categoriesCount = Category::count();
        $reviewsCount = Review::count();

        $months = collect(range(0, 5))->map(function ($offset) {
            return now()->subMonths(5 - $offset);
        });

        $monthlyTotalsRaw = Product::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month_key, COUNT(*) as total')
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $chartLabels = $months->map(fn ($month) => $month->format('M'))->values();
        $chartData = $months->map(function ($month) use ($monthlyTotalsRaw) {
            return (int) ($monthlyTotalsRaw[$month->format('Y-m')] ?? 0);
        })->values();

        $reviewStatusRaw = Review::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $totalReviews = max(1, (int) Review::count());

        $channelStats = collect(['approved', 'pending', 'rejected'])->map(function ($status) use ($reviewStatusRaw, $totalReviews) {
            $count = (int) ($reviewStatusRaw[$status] ?? 0);
            return [
                'name' => ucfirst($status),
                'count' => $count,
                'percent' => (int) round(($count / $totalReviews) * 100),
            ];
        });

        $recentProducts = Product::latest()->take(8)->get(['id', 'name', 'stock', 'price', 'status']);

        $topProducts = Product::withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'sold_qty' => (int) $product->reviews_count,
                ];
            });

        return view('admin.dashboard', [
            'productsCount' => $productsCount,
            'brandsCount' => $brandsCount,
            'categoriesCount' => $categoriesCount,
            'reviewsCount' => $reviewsCount,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'channelStats' => $channelStats,
            'recentProducts' => $recentProducts,
            'topProducts' => $topProducts,
        ]);
    }
}
