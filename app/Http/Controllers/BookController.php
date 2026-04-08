<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        
        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }
        
        // Sort functionality
        if ($request->has('sort')) {
            switch($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $books = $query->paginate(12);
        
        // Get categories with counts for sidebar
        $categories = Book::select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->get();
        
        $totalBooks = Book::count();
        
        return view('books.index', compact('books', 'categories', 'totalBooks'));
    }
    
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }
}