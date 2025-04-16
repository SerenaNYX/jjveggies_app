<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDER_PLACED = 'order_placed';
    case PREPARING = 'preparing';
    case PACKED = 'packed';
    case DELIVERING = 'delivering';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::ORDER_PLACED => 'Order Placed',
            self::PREPARING => 'Preparing',
            self::PACKED => 'Packed',
            self::DELIVERING => 'Out for Delivery',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}