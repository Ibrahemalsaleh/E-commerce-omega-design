<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * البحث عن المنتجات
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->input('query');
        
        // البحث في المنتجات
        $products = Product::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        });
        
        // التصفية حسب المجموعة
        if ($request->has('collection_id')) {
            $products->whereHas('collections', function ($q) use ($request) {
                $q->where('collections.id', $request->collection_id);
            });
        }
        
        // الترتيب
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                default:
                    $products->orderBy('created_at', 'desc');
            }
        } else {
            $products->orderBy('created_at', 'desc');
        }
        
        $resultsCount = $products->count();
        $products = $products->paginate(12);
        
        // تسجيل عملية البحث
        if ($request->user()) {
            Search::create([
                'user_id' => $request->user()->id,
                'query' => $query,
                'results_count' => $resultsCount
            ]);
        } else {
            Search::create([
                'query' => $query,
                'results_count' => $resultsCount
            ]);
        }
        
        return view('search.results', compact('products', 'query', 'resultsCount'));
    }
}