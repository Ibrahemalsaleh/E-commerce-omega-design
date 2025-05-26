<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Collection;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);
        
        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by collection if provided
        if ($request->filled('collection')) {
            $query->whereHas('collections', function($q) use ($request) {
                $q->where('collections.id', $request->collection);
            });
        }
        
        // Filter by price range if provided
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Get all available collections for the filter dropdown
        $collections = Collection::all();
        
        // Get all product categories for the filter buttons
        $categories = Product::select('category')->distinct()->whereNotNull('category')->get()->pluck('category');
        
        $products = $query->paginate(12);
        
        return view('products.index', compact('products', 'collections', 'categories'));
    }
    
    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Get related products from the same category or collections
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function($query) use ($product) {
                // Same category
                if ($product->category) {
                    $query->where('category', $product->category);
                }
                
                // Or same collection
                if ($product->collections->isNotEmpty()) {
                    $collectionIds = $product->collections->pluck('id');
                    $query->orWhereHas('collections', function($q) use ($collectionIds) {
                        $q->whereIn('collections.id', $collectionIds);
                    });
                }
            })
            ->where('is_active', true)
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
}