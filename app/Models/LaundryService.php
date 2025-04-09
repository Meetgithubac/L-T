<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LaundryService extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'service_type',
        'unit',
        'estimated_hours',
        'is_available',
        'provider_id',
        'average_rating',
        'reviews_count'
    ];
    
    /**
     * Get the provider that owns the laundry service.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    
    /**
     * Get the order items for the laundry service.
     */
    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'service');
    }
    
    /**
     * Get the reviews for the laundry service.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    
    /**
     * Get the rating stars HTML.
     *
     * @return string
     */
    public function getRatingStarsHtml()
    {
        if (!$this->average_rating) {
            return '<span class="text-muted">No ratings yet</span>';
        }
        
        $fullStars = floor($this->average_rating);
        $halfStar = ($this->average_rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        $html = str_repeat('<i class="fas fa-star text-warning"></i>', $fullStars);
        
        if ($halfStar) {
            $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
        }
        
        $html .= str_repeat('<i class="far fa-star text-warning"></i>', $emptyStars);
        
        $html .= ' <span class="text-muted">(' . $this->reviews_count . ')</span>';
        
        return $html;
    }
}
