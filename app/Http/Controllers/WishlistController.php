<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('book')
                                 ->where('user_id', Auth::id())
                                 ->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    // This handles the toggle (add/remove)
   public function toggle(Book $book)
{
    // Check if user is logged in
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'error' => 'Please login to like books',
            'login_url' => route('login')
        ], 401);
    }
    
    $userId = Auth::id();
    $wishlist = Wishlist::where('user_id', $userId)
                        ->where('book_id', $book->id)
                        ->first();
    
    if ($wishlist) {
        $wishlist->delete();
        $liked = false;
        $message = 'Book removed from wishlist';
    } else {
        Wishlist::create([
            'user_id' => $userId,
            'book_id' => $book->id
        ]);
        $liked = true;
        $message = 'Book added to wishlist';
    }
    
    return response()->json([
        'success' => true,
        'liked' => $liked,
        'message' => $message
    ]);
}

    public function add(Book $book)
    {
        return $this->toggle($book);
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }
        
        $wishlist->delete();
        
        return redirect()->route('wishlist.index')->with('success', 'Book removed from wishlist!');
    }

    public function moveToCart(Wishlist $wishlist)
{
    if ($wishlist->user_id !== Auth::id()) {
        abort(403);
    }
    
    // Check if item already exists in cart
    $cartItem = Cart::where('user_id', Auth::id())
                    ->where('book_id', $wishlist->book_id)
                    ->first();
    
    if ($cartItem) {
        $cartItem->increment('quantity');
    } else {
        Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $wishlist->book_id,
            'quantity' => 1
        ]);
    }
    
    // Remove from wishlist after moving to cart
    $wishlist->delete();
    
    return redirect()->route('wishlist.index')->with('success', 'Book moved to cart successfully!');
}

}