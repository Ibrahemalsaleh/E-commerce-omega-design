<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        // Si la vista está directamente en resources/views/
        return view('about.index');
        
        // O si decides moverla a una subcarpeta 'pages', descomenta la siguiente línea
        // return view('pages.about');
    }
}