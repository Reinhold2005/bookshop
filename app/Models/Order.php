<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'delivery_method',
        'delivery_address',
        'city',
        'postal_code',
        'phone',
        'estimated_delivery',
        'actual_delivery_date',
        'tracking_number',
        'admin_notes'
    ];
    
    protected $casts = [
        'estimated_delivery' => 'date',
        'actual_delivery_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Get formatted payment method
public function getFormattedPaymentMethodAttribute()
{
    $methods = [
        'stripe' => 'Credit Card (Stripe)',
        'cash_on_delivery' => 'Cash on Delivery',
        'bank_transfer' => 'Bank Transfer'
    ];
    return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
}
    // Get status badge color
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Processing' => 'bg-blue-100 text-blue-800',
            'Shipped' => 'bg-purple-100 text-purple-800',
            'Out For Delivery' => 'bg-orange-100 text-orange-800',
            'Delivered' => 'bg-green-100 text-green-800',
            'Cancelled' => 'bg-red-100 text-red-800'
        ];
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    
    // Get delivery method badge
    // Get formatted delivery method
public function getFormattedDeliveryMethodAttribute()
{
    $methods = [
        'standard' => 'Standard Delivery (5-7 days)',
        'express' => 'Express Delivery (2-3 days)',
        'next_day' => 'Next Day Delivery'
    ];
    return $methods[$this->delivery_method] ?? ucfirst(str_replace('_', ' ', $this->delivery_method));
}
}