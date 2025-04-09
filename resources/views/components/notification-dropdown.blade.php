@props(['notifications'])

<div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="notification-dropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
    <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Notifications</h6>
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>
    
    @forelse($notifications as $notification)
        <div class="dropdown-item-text p-2 border-bottom notification-item" style="background-color: rgba(13, 110, 253, 0.05);">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
                <div class="d-flex gap-2">
                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm p-0" title="Mark as read">
                            <i class="bi bi-check text-primary"></i>
                        </button>
                    </form>
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm p-0" title="Delete">
                            <i class="bi bi-x text-danger"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            @if($notification->data['type'] == 'order_status')
                <p class="mb-1">
                    <span class="fw-bold">Order #{{ $notification->data['order_number'] ?? $notification->data['order_id'] }}</span>:
                    @if($notification->data['message'])
                        {{ $notification->data['message'] }}
                    @else
                        Status changed to <span class="fw-bold text-capitalize">{{ $notification->data['status'] }}</span>
                    @endif
                </p>
                
                @if(isset($notification->data['has_location']) && $notification->data['has_location'])
                    <p class="small text-success mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i> Location tracking available
                    </p>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> View Order
                        </a>
                        <a href="{{ route('orders.show', $notification->data['order_id']) }}#track-order" class="btn btn-sm btn-success">
                            <i class="fas fa-map-marker-alt me-1"></i> Track Location
                        </a>
                    </div>
                @else
                    <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="btn btn-sm btn-primary">
                        View Order
                    </a>
                @endif
            @else
                <p class="mb-1">{{ $notification->data['message'] ?? 'New notification' }}</p>
            @endif
        </div>
    @empty
        <div class="dropdown-item-text p-3 text-center">
            <p class="text-muted mb-0">No new notifications</p>
        </div>
    @endforelse
    
    @if($notifications->count() > 0)
        <div class="p-2 text-center border-top">
            <a href="{{ route('notifications.index') }}" class="text-decoration-none">
                View all notifications
            </a>
        </div>
    @endif
</div>