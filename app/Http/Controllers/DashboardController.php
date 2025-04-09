<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LaundryService;
use App\Models\TiffinService;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Admin dashboard
        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }
        
        // Service Provider dashboard
        if ($user->hasAnyRole(['service_provider', 'laundry_provider', 'tiffin_provider'])) {
            return $this->providerDashboard();
        }
        
        // Customer dashboard
        return $this->customerDashboard();
    }
    
    /**
     * Display admin dashboard
     */
    private function adminDashboard()
    {
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        $userStats = [
            'total' => User::count(),
            'customers' => User::role('customer')->count(),
            'providers' => User::whereHas('roles', function($query) {
                $query->whereIn('name', ['service_provider', 'laundry_provider', 'tiffin_provider']);
            })->count(),
        ];
        
        $serviceStats = [
            'laundry' => LaundryService::count(),
            'tiffin' => TiffinService::count(),
        ];
        
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        
        return view('dashboard.admin', compact('orderStats', 'userStats', 'serviceStats', 'recentOrders'));
    }
    
    /**
     * Display provider dashboard
     */
    private function providerDashboard()
    {
        $user = auth()->user();
        
        $orderStats = [
            'assigned' => Order::where('assigned_to', $user->id)->count(),
            'pending' => Order::where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'processing' => Order::where('assigned_to', $user->id)->where('status', 'processing')->count(),
            'completed' => Order::where('assigned_to', $user->id)
                ->whereIn('status', ['completed', 'delivered'])->count(),
        ];
        
        // Get services based on provider type
        $services = collect();
        if ($user->hasRole('laundry_provider') || $user->hasRole('service_provider')) {
            $laundryServices = LaundryService::where('provider_id', $user->id)->get();
            $services = $services->concat($laundryServices);
        }
        
        if ($user->hasRole('tiffin_provider') || $user->hasRole('service_provider')) {
            $tiffinServices = TiffinService::where('provider_id', $user->id)->get();
            $services = $services->concat($tiffinServices);
        }
        
        $recentOrders = Order::where('assigned_to', $user->id)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
            
        return view('dashboard.provider', compact('orderStats', 'services', 'recentOrders'));
    }
    
    /**
     * Display customer dashboard
     */
    private function customerDashboard()
    {
        $user = auth()->user();
        
        $orderStats = [
            'total' => Order::where('user_id', $user->id)->count(),
            'pending' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', $user->id)->where('status', 'processing')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'delivered' => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'cancelled' => Order::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];
        
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
            
        $recommendedLaundryServices = LaundryService::where('is_available', true)
            ->inRandomOrder()
            ->limit(2)
            ->get();
            
        $recommendedTiffinServices = TiffinService::where('is_available', true)
            ->inRandomOrder()
            ->limit(2)
            ->get();
            
        return view('dashboard.customer', compact(
            'orderStats', 
            'recentOrders', 
            'recommendedLaundryServices', 
            'recommendedTiffinServices'
        ));
    }
}