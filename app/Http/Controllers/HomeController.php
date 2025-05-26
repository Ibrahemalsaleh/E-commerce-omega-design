<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Collection;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get featured collections for the main categories section
        $featuredCollections = Collection::where('is_featured', true)
            ->take(3)
            ->get();
            
        // Get new arrivals products
        $newArrivals = Product::where('is_new_arrival', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
            
        // Get bestseller products
        $bestsellers = Product::where('is_bestseller', true)
            ->take(4)
            ->get();
            
        // Get testimonials
        $testimonials = Testimonial::where('is_active', true)
        ->whereNotNull('name')
        ->whereNotNull('text')
        ->where('rating', '>', 0)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();   
        return view('home', compact(
            'featuredCollections',
            'newArrivals',
            'bestsellers',
            'testimonials'
        ));





    }

}