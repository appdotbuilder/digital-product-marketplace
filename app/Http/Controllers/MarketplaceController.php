<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class MarketplaceController extends Controller
{
    /**
     * Display the marketplace homepage.
     */
    public function index()
    {
        $featuredProducts = Product::with(['seller', 'category'])
            ->active()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::active()
            ->orderBy('sort_order')
            ->get();

        $recentProducts = Product::with(['seller', 'category'])
            ->active()
            ->latest()
            ->take(12)
            ->get();

        $stats = [
            'total_products' => Product::active()->count(),
            'total_sellers' => Product::distinct('user_id')->count(),
            'total_sales' => \App\Models\Order::where('status', 'completed')->count(),
        ];

        return Inertia::render('marketplace/index', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'recentProducts' => $recentProducts,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified resource (category).
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::with(['seller', 'category'])
            ->where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(12);

        return Inertia::render('marketplace/category', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}