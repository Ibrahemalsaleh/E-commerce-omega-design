<?php

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);
        
        Newsletter::create([
            'email' => $request->email
        ]);
        
        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}