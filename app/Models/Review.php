<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    // Polymorphic relationship to either LaundryService or TiffinService
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper method to get HTML for star ratings
    public function getRatingStarsHtml()
    {
        $html = '<div class="text-warning">';
        
        // Add filled stars
        for ($i = 1; $i <= $this->rating; $i++) {
            $html .= '<i class="fas fa-star"></i>';
        }
        
        // Add empty stars
        for ($i = $this->rating + 1; $i <= 5; $i++) {
            $html .= '<i class="far fa-star"></i>';
        }
        
        $html .= '</div>';
        
        return $html;
    }

    // Scope for approved reviews
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope for pending reviews
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Scope for filtering by service type
    public function scopeOfServiceType($query, $type)
    {
        if ($type === 'laundry') {
            return $query->where('reviewable_type', 'App\Models\LaundryService');
        } elseif ($type === 'tiffin') {
            return $query->where('reviewable_type', 'App\Models\TiffinService');
        }
        
        return $query;
    }
}