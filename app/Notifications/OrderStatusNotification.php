<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    protected $order;
    
    /**
     * @var string|null
     */
    protected $customMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, ?string $customMessage = null)
    {
        $this->order = $order;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Order #' . $this->order->order_number . ' Status Update')
            ->greeting('Hello ' . $notifiable->name . ',');
        
        if ($this->customMessage) {
            $message->line($this->customMessage);
        } else {
            $statusMessage = match($this->order->status) {
                'pending' => 'Your order has been placed and is awaiting confirmation.',
                'confirmed' => 'Your order has been confirmed and is being processed.',
                'processing' => 'Your order is now being processed.',
                'ready for delivery' => 'Your order is ready for delivery.',
                'delivered' => 'Your order has been delivered. Thank you for using our service!',
                'completed' => 'Your order has been completed. Thank you for using our service!',
                'cancelled' => 'Your order has been cancelled.',
                default => 'Your order status has been updated.'
            };
            
            $message->line($statusMessage);
        }
        
        $message->line('Order #: ' . $this->order->order_number)
            ->line('Status: ' . ucfirst($this->order->status))
            ->line('Total Amount: ₹' . number_format($this->order->total_amount, 2));
        
        // Add location tracking info if available
        if ($this->order->latitude && $this->order->longitude && $this->order->location_updated_at) {
            $message->line('Your order can now be tracked in real-time.');
            $message->line('Last location update: ' . $this->order->location_updated_at->diffForHumans());
        }
        // Or if delivery location is available
        else if ($this->order->delivery_latitude && $this->order->delivery_longitude) {
            $message->line('Delivery location is available on your order tracking page.');
        }
        
        return $message->action('View Order Details', url(route('orders.show', $this->order->id)))
            ->line('Thank you for using our services!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'type' => 'order_status',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'order_date' => $this->order->created_at->format('Y-m-d H:i:s'),
            'total_amount' => $this->order->total_amount,
            'message' => $this->customMessage,
        ];
        
        // Add location data if current tracking location is available
        if ($this->order->latitude && $this->order->longitude) {
            $data['has_location'] = true;
            $data['location'] = [
                'latitude' => $this->order->latitude,
                'longitude' => $this->order->longitude,
                'updated_at' => $this->order->location_updated_at?->format('Y-m-d H:i:s')
            ];
        } 
        // Or if at least delivery location is available (destination)
        else if ($this->order->delivery_latitude && $this->order->delivery_longitude) {
            $data['has_location'] = true;
            $data['location'] = [
                'delivery_latitude' => $this->order->delivery_latitude,
                'delivery_longitude' => $this->order->delivery_longitude
            ];
        } 
        else {
            $data['has_location'] = false;
        }
        
        return $data;
    }
}