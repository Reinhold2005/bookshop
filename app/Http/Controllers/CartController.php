<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('book')
                        ->where('user_id', Auth::id())
                        ->get();
        
        $total = $cartItems->sum(function($item) {
            return $item->book->price * $item->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    public function add(Book $book)
    {
        // Check if book already in cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('book_id', $book->id)
                        ->first();
        
        if ($cartItem) {
            // Increment quantity if already in cart
            $cartItem->increment('quantity');
            $message = 'Quantity updated in cart!';
        } else {
            // Add new item to cart
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => 1
            ]);
            $message = 'Book added to cart!';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
        
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Book removed from cart!');
    }
    
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart->update(['quantity' => $request->quantity]);
        
        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    // Get cart items for AJAX
public function ajaxCart()
{
    $cartItems = Cart::with('book')->where('user_id', Auth::id())->get();
    
    $items = [];
    foreach ($cartItems as $item) {
        $items[] = [
            'id' => $item->id,
            'book_id' => $item->book_id,
            'title' => $item->book->title,
            'price' => floatval($item->book->price),
            'quantity' => $item->quantity,
            'total' => floatval($item->book->price * $item->quantity)
        ];
    }
    
    return response()->json([
        'success' => true,
        'items' => $items,
        'total_items' => $cartItems->sum('quantity'),
        'total_price' => $cartItems->sum(function($item) {
            return $item->book->price * $item->quantity;
        })
    ]);
}

// Get cart count for badge
public function getCartCount()
{
    $count = Cart::where('user_id', Auth::id())->sum('quantity');
    return response()->json(['count' => $count]);
}

// Update cart quantity via AJAX
public function updateAjax(Request $request)
{
    $request->validate([
        'cart_id' => 'required|exists:carts,id',
        'quantity' => 'required|integer|min:1'
    ]);
    
    $cart = Cart::find($request->cart_id);
    
    if ($cart->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }
    
    $cart->update(['quantity' => $request->quantity]);
    
    return response()->json(['success' => true]);
}

// Remove cart item via AJAX
public function removeAjax(Request $request)
{
    $request->validate([
        'cart_id' => 'required|exists:carts,id'
    ]);
    
    $cart = Cart::find($request->cart_id);
    
    if ($cart->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }
    
    $cart->delete();
    
    return response()->json(['success' => true]);
} 
}