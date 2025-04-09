<?php

namespace App\Http\Controllers;

use App\Models\LaundryService;
use App\Models\TiffinService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     */
    public function index()
    {
        // Get featured services for the homepage
        $featuredLaundryServices = LaundryService::where('is_available', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
            
        $featuredTiffinServices = TiffinService::where('is_available', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
            
        return view('home', compact('featuredLaundryServices', 'featuredTiffinServices'));
    }
    
    /**
     * Show the about us page
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('contact');
    }
    
    /**
     * Process a contact form submission
     */
    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or store the inquiry
        // For now, just redirect back with a success message
        
        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}