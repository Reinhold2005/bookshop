<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rate(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);
        
        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'book_id' => $book->id
            ],
            [
                'rating' => $request->rating
            ]
        );
        
        // Update book average rating
        $averageRating = $book->ratings()->avg('rating');
        $totalRatings = $book->ratings()->count();
        
        $book->update([
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $totalRatings
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'average_rating' => round($averageRating, 1),
                'total_ratings' => $totalRatings,
                'user_rating' => $request->rating,
                'message' => 'Thank you for rating!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Thank you for rating!');
    }
}