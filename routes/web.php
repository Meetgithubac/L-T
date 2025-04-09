<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaundryServiceController;
use App\Http\Controllers\TiffinServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContactForm'])->name('contact.submit');

// Download Project ZIP
Route::get('/download-project', function () {
    $zipPath = base_path('../L-T.zip');
    
    if (file_exists($zipPath)) {
        return response()->download($zipPath);
    }
    
    return response()->json(['error' => 'Zip file not found'], 404);
});

// Authentication Routes (provided by laravel/ui)
Auth::routes();

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Laundry Service Routes
    Route::get('/laundry', [LaundryServiceController::class, 'index'])->name('laundry.index');
    Route::get('/laundry/{laundryService}', [LaundryServiceController::class, 'show'])->name('laundry.show');
    
    // Tiffin Service Routes
    Route::get('/tiffin', [TiffinServiceController::class, 'index'])->name('tiffin.index');
    Route::get('/tiffin/{tiffinService}', [TiffinServiceController::class, 'show'])->name('tiffin.show');
    
    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/update-location', [OrderController::class, 'updateLocation'])->name('orders.update-location');
    
    // Provider Routes (require specific permissions)
    Route::middleware(['can:edit laundry services'])->group(function () {
        Route::get('/laundry/create', [LaundryServiceController::class, 'create'])->name('laundry.create');
        Route::post('/laundry', [LaundryServiceController::class, 'store'])->name('laundry.store');
        Route::get('/laundry/{laundryService}/edit', [LaundryServiceController::class, 'edit'])->name('laundry.edit');
        Route::put('/laundry/{laundryService}', [LaundryServiceController::class, 'update'])->name('laundry.update');
        Route::delete('/laundry/{laundryService}', [LaundryServiceController::class, 'destroy'])->name('laundry.destroy');
    });
    
    Route::middleware(['can:edit tiffin services'])->group(function () {
        Route::get('/tiffin/create', [TiffinServiceController::class, 'create'])->name('tiffin.create');
        Route::post('/tiffin', [TiffinServiceController::class, 'store'])->name('tiffin.store');
        Route::get('/tiffin/{tiffinService}/edit', [TiffinServiceController::class, 'edit'])->name('tiffin.edit');
        Route::put('/tiffin/{tiffinService}', [TiffinServiceController::class, 'update'])->name('tiffin.update');
        Route::delete('/tiffin/{tiffinService}', [TiffinServiceController::class, 'destroy'])->name('tiffin.destroy');
    });
    
    // Admin and Provider Routes
    Route::middleware(['can:edit orders'])->group(function () {
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    });
    
    // Notification Routes
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationsController::class, 'destroy'])->name('notifications.destroy');
    
    // Review Routes - moved to routes/reviews.php
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
});