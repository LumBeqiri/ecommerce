<?php

namespace App\values;

enum OrderStatusTypes: string
{
    case PENDING = 'pending'; // Order placed but not yet processed
    case PROCESSING = 'processing'; // Order is being prepared
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled'; // Order has been cancelled
    case REFUNDED = 'refunded'; // Order has been refunded
    case ON_HOLD = 'on_hold';
    case COMPLETED = 'completed'; // Order is fulfilled successfully
    case RETURN_REQUESTED = 'return_requested'; // Customer requested a return
    case RETURNED = 'returned'; 
}
