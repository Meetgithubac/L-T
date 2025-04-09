@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Notifications') }}</h5>
                    @if(count($notifications) > 0)
                        <div class="d-flex gap-2">
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    {{ __('Mark All as Read') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @if(count($notifications) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr class="{{ $notification->read_at ? '' : 'table-light fw-bold' }}">
                                            <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                @if(isset($notification->data['type']) && $notification->data['type'] == 'order_status')
                                                    <span class="fw-bold">Order #{{ $notification->data['order_id'] }}</span>:
                                                    Status changed to <span class="badge bg-{{ $notification->data['status'] == 'completed' ? 'success' : ($notification->data['status'] == 'cancelled' ? 'danger' : 'primary') }}">
                                                        {{ ucfirst($notification->data['status']) }}
                                                    </span>
                                                    <div class="mt-2">
                                                        <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="btn btn-sm btn-primary">
                                                            {{ __('View Order') }}
                                                        </a>
                                                    </div>
                                                @else
                                                    {{ $notification->data['message'] ?? 'New notification' }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if(!$notification->read_at)
                                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                {{ __('Mark as Read') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Are you sure you want to delete this notification?') }}')">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                            <h5>{{ __('No Notifications') }}</h5>
                            <p class="text-muted">{{ __('You have no notifications at this time.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection