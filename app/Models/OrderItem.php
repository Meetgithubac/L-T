<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'service_type',
        'service_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes'
    ];
    
    /**
     * Get the order that this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the service model (either LaundryService or TiffinService).
     */
    public function service(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Calculate the subtotal for this item (quantity * unit_price).
     */
    public function calculateSubtotal(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
