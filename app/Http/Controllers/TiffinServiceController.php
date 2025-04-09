<?php

namespace App\Http\Controllers;

use App\Models\TiffinService;
use Illuminate\Http\Request;

class TiffinServiceController extends Controller
{
    /**
     * Display a listing of the tiffin services.
     */
    public function index()
    {
        $tiffinServices = TiffinService::where('is_available', true)->paginate(8);
        return view('tiffin.index', compact('tiffinServices'));
    }
    
    /**
     * Show the form for creating a new tiffin service.
     */
    public function create()
    {
        $this->authorize('create tiffin services');
        return view('tiffin.create');
    }
    
    /**
     * Store a newly created tiffin service in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create tiffin services');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'meal_type' => 'required|in:breakfast,lunch,dinner,combo',
            'cuisine' => 'required|in:indian,chinese,italian,thai,mexican,continental,mixed',
            'menu_items' => 'required|array',
            'menu_items.*' => 'required|string',
        ]);
        
        $validated['is_vegetarian'] = $request->has('is_vegetarian');
        $validated['is_available'] = $request->has('is_available');
        $validated['provider_id'] = auth()->id();
        
        $tiffinService = new TiffinService($validated);
        $tiffinService->save();
        
        return redirect()->route('tiffin.index')
            ->with('success', 'Tiffin service created successfully.');
    }
    
    /**
     * Display the specified tiffin service.
     */
    public function show(TiffinService $tiffinService)
    {
        return view('tiffin.show', compact('tiffinService'));
    }
    
    /**
     * Show the form for editing the specified tiffin service.
     */
    public function edit(TiffinService $tiffinService)
    {
        $this->authorize('edit tiffin services');
        
        if ($tiffinService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('tiffin.edit', compact('tiffinService'));
    }
    
    /**
     * Update the specified tiffin service in storage.
     */
    public function update(Request $request, TiffinService $tiffinService)
    {
        $this->authorize('edit tiffin services');
        
        if ($tiffinService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'meal_type' => 'required|in:breakfast,lunch,dinner,combo',
            'cuisine' => 'required|in:indian,chinese,italian,thai,mexican,continental,mixed',
            'menu_items' => 'required|array',
            'menu_items.*' => 'required|string',
        ]);
        
        $validated['is_vegetarian'] = $request->has('is_vegetarian');
        $validated['is_available'] = $request->has('is_available');
        
        $tiffinService->update($validated);
        
        return redirect()->route('tiffin.index')
            ->with('success', 'Tiffin service updated successfully.');
    }
    
    /**
     * Remove the specified tiffin service from storage.
     */
    public function destroy(TiffinService $tiffinService)
    {
        $this->authorize('delete tiffin services');
        
        if ($tiffinService->provider_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $tiffinService->delete();
        
        return redirect()->route('tiffin.index')
            ->with('success', 'Tiffin service deleted successfully.');
    }
}