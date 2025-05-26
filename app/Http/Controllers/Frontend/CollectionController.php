<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the collections.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the category filter from request
        $category = $request->get('category');
        
        // Start building the query
        $query = Collection::where('is_active', true);
        
        // Apply category filter if provided
        if ($category) {
            $query->where('category', $category);
        }
        
        // Get collections with pagination
        $collections = $query->orderBy('created_at', 'desc')
                            ->paginate(12);
        
        return view('collections.index', [
            'collections' => $collections
        ]);
    }

    /**
     * Display the specified collection and its products.
     *
     * @param Request $request
     * @param Collection $collection
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Collection $collection)
    {
        // Check if collection is active
        if (!$collection->is_active) {
            abort(404);
        }
        
        // Get the sort parameter from request
        $sort = $request->get('sort', 'latest');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        
        // Start building the query for products in this collection
        $query = $collection->products()->where('products.is_active', true);
        
        // Apply price filters if provided
        if ($minPrice) {
            $query->where('products.price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('products.price', '<=', $maxPrice);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('products.price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('products.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('products.name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }
        
        // Get products with pagination
        $products = $query->paginate(12);
        
        // Get related collections (same category, but not the current one)
        $relatedCollections = Collection::where('category', $collection->category)
                                       ->where('id', '!=', $collection->id)
                                       ->where('is_active', true)
                                       ->limit(3)
                                       ->get();
        
        return view('collections.show', [
            'collection' => $collection,
            'products' => $products,
            'relatedCollections' => $relatedCollections
        ]);
    }

    /**
     * Filter collections by category (AJAX endpoint if needed)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $category = $request->get('category');
        
        $query = Collection::where('is_active', true);
        
        if ($category && $category != 'all') {
            $query->where('category', $category);
        }
        
        $collections = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'collections' => $collections
        ]);
    }
}