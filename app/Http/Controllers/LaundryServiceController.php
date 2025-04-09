<?php

namespace App\Http\Controllers;

use App\Models\LaundryService;
use Illuminate\Http\Request;

class LaundryServiceController extends Controller
{
    /**
     * Display a listing of the laundry services.
     */
    public function index()
    {
        $laundryServices = LaundryService::where('is_available', true)->paginate(8);
        return view('laundry.index', compact('laundryServices'));
    }
    
    /**
     * Show the form for creating a new laundry service.
     */
    public function create()
    {
        $this->authorize('create laundry services');
        return view('laundry.create');
    }
    
    /**
     * Store a newly created laundry service in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create laundry services');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'service_type' => 'required|in:wash,dry_clean,iron,fold,package_deal',
            'unit' => 'required|in:per_kg,per_piece,per_bundle',
            'estimated_hours' => 'required|integer|min:1',
        ]);
        
        $validated['is_available'] = $request->has('is_available');
        $validated['provider_id'] = auth()->id();
        
        $laundryService = new LaundryService($validated);
        $laundryService->save();
        
        return redirect()->route('laundry.index')
            ->with('success', 'Laundry service created successfully.');
    }
    
    /**
     * Display the specified laundry service.
     */
    public function show(LaundryService $laundryService)
    {
        return view('laundry.show', compact('laundryService'));
    }
    
    /**
     * Show the form for editing the specified laundry service.
     */
    public function edit(LaundryService $laundryService)
    {
        $this->authorize('edit laundry services');
        
        if ($laundryService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('laundry.edit', compact('laundryService'));
    }
    
    /**
     * Update the specified laundry service in storage.
     */
    public function update(Request $request, LaundryService $laundryService)
    {
        $this->authorize('edit laundry services');
        
        if ($laundryService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'service_type' => 'required|in:wash,dry_clean,iron,fold,package_deal',
            'unit' => 'required|in:per_kg,per_piece,per_bundle',
            'estimated_hours' => 'required|integer|min:1',
        ]);
        
        $validated['is_available'] = $request->has('is_available');
        
        $laundryService->update($validated);
        
        return redirect()->route('laundry.index')
            ->with('success', 'Laundry service updated successfully.');
    }
    
    /**
     * Remove the specified laundry service from storage.
     */
    public function destroy(LaundryService $laundryService)
    {
        $this->authorize('delete laundry services');
        
        if ($laundryService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $laundryService->delete();
        
        return redirect()->route('laundry.index')
            ->with('success', 'Laundry service deleted successfully.');
    }
}