<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_number',
        'order_type',
        'status',
        'total_amount',
        'delivery_address',
        'delivery_phone',
        'delivery_time',
        'special_instructions',
        'payment_method',
        'is_paid',
        'latitude',
        'longitude',
        'location_updated_at',
        'delivery_latitude',
        'delivery_longitude',
        'cancellation_reason',
        'assigned_to'
    ];
    
    protected $casts = [
        'is_paid' => 'boolean',
        'delivery_time' => 'datetime',
        'location_updated_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'delivery_latitude' => 'float',
        'delivery_longitude' => 'float',
    ];
    
    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the service provider assigned to this order.
     */
    public function assignedProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    /**
     * Get the order items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the reviews for this order.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = strtoupper(substr(md5(time()), 0, 3));
        $timestamp = date('YmdHi');
        $random = mt_rand(100, 999);
        
        return $prefix . '-' . $timestamp . '-' . $random;
    }
    
    /**
     * Check if the order can be reviewed
     */
    public function canBeReviewed(): bool
    {
        // Order can be reviewed if it's delivered/completed and hasn't been reviewed yet
        return in_array($this->status, ['delivered', 'completed']) && $this->reviews()->count() === 0;
    }
}
