<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('book')->where('user_id', Auth::id())->get();
        $total = $cartItems->sum(function($item) {
            return $item->book->price * $item->quantity;
        });
        
        // Delivery options with estimated times
        $deliveryOptions = [
            'standard' => [
                'name' => 'Standard Delivery',
                'price' => 5.99,
                'days' => '5-7 business days',
                'icon' => 'fa-truck'
            ],
            'express' => [
                'name' => 'Express Delivery',
                'price' => 12.99,
                'days' => '2-3 business days',
                'icon' => 'fa-rocket'
            ],
            'next_day' => [
                'name' => 'Next Day Delivery',
                'price' => 19.99,
                'days' => '1 business day',
                'icon' => 'fa-bolt'
            ]
        ];
        
        // Payment methods
        $paymentMethods = [
            'stripe' => 'Credit/Debit Card (Stripe)',
            'cash_on_delivery' => 'Cash on Delivery',
            'bank_transfer' => 'Bank Transfer'
        ];
        
        return view('checkout.index', compact('cartItems', 'total', 'deliveryOptions', 'paymentMethods'));
    }
    
    public function createCheckoutSession(Request $request)
    {
        // Validate delivery and payment info
        $validated = $request->validate([
            'delivery_method' => 'required|in:standard,express,next_day',
            'payment_method' => 'required|in:stripe,cash_on_delivery,bank_transfer',
            'delivery_address' => 'required|string|min:10',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required|string|min:10'
        ]);
        
        // Store delivery info in session
        session([
            'delivery_method' => $request->delivery_method,
            'payment_method' => $request->payment_method,
            'delivery_address' => $request->delivery_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone
        ]);
        
        $cartItems = Cart::with('book')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }
        
        // Delivery prices
        $deliveryPrices = [
            'standard' => 5.99,
            'express' => 12.99,
            'next_day' => 19.99
        ];
        
        $deliveryPrice = $deliveryPrices[$request->delivery_method];
        $subtotal = $cartItems->sum(function($item) {
            return $item->book->price * $item->quantity;
        });
        $total = $subtotal + $deliveryPrice;
        
        // If payment is cash on delivery or bank transfer, process order directly
        if ($request->payment_method != 'stripe') {
            return $this->processOrder($request, $cartItems, $subtotal, $deliveryPrice, $total);
        }
        
        // Stripe payment
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->book->title,
                        'description' => 'by ' . $item->book->author,
                    ],
                    'unit_amount' => intval($item->book->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }
        
        // Add delivery fee as a line item
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Delivery Fee',
                    'description' => $request->delivery_method . ' delivery',
                ],
                'unit_amount' => intval($deliveryPrice * 100),
            ],
            'quantity' => 1,
        ];
        
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);
        
        return redirect($checkoutSession->url);
    }
    
    private function processOrder($request, $cartItems, $subtotal, $deliveryPrice, $total)
    {
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryPrice,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => $request->payment_method == 'cash_on_delivery' ? 'pending' : 'pending',
            'payment_method' => $request->payment_method,
            'delivery_method' => $request->delivery_method,
            'delivery_address' => $request->delivery_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'estimated_delivery' => $this->getEstimatedDelivery($request->delivery_method)
        ]);
        
        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->book->price,
            ]);
            
            // Reduce stock
            $book = Book::find($item->book_id);
            if ($book) {
                $book->decrement('stock', $item->quantity);
                $book->increment('sold_count', $item->quantity);
            }
        }
        
        // Clear cart
        Cart::where('user_id', Auth::id())->delete();
        
        // Send order confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send email: ' . $e->getMessage());
        }
        
        return redirect()->route('checkout.success')->with('order', $order);
    }
    
    private function getEstimatedDelivery($method)
    {
        $estimates = [
            'standard' => now()->addDays(7)->format('F d, Y'),
            'express' => now()->addDays(3)->format('F d, Y'),
            'next_day' => now()->addDay()->format('F d, Y')
        ];
        return $estimates[$method];
    }
    
    public function success(Request $request)
    {
        // Get cart items if order not already created
        $cartItems = Cart::with('book')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isNotEmpty()) {
            // Process order for Stripe payment
            $subtotal = $cartItems->sum(function($item) {
                return $item->book->price * $item->quantity;
            });
            
            $deliveryPrices = [
                'standard' => 5.99,
                'express' => 12.99,
                'next_day' => 19.99
            ];
            $deliveryMethod = session('delivery_method', 'standard');
            $deliveryPrice = $deliveryPrices[$deliveryMethod];
            $total = $subtotal + $deliveryPrice;
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryPrice,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'paid',
                'payment_method' => 'stripe',
                'delivery_method' => session('delivery_method'),
                'delivery_address' => session('delivery_address'),
                'city' => session('city'),
                'postal_code' => session('postal_code'),
                'phone' => session('phone'),
                'estimated_delivery' => $this->getEstimatedDelivery(session('delivery_method', 'standard'))
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ]);
  
                // Reduce stock
                $book = Book::find($item->book_id);
                if ($book) {
                    $book->decrement('stock', $item->quantity);
                }
            }
            
            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            // Send order confirmation email
            try {
                Mail::to(Auth::user()->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send email: ' . $e->getMessage());
            }
        } else {
            $order = Order::latest()->where('user_id', Auth::id())->first();
        }
        
        return view('checkout.success', compact('order'));
    }
    
    public function cancel()
    {
        return view('checkout.cancel');
    }
}