<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
   public function index(Request $request)
{
    $query = Book::query();
    
    // Apply filters
    if ($request->filter == 'low_stock') {
        $query->whereColumn('stock', '<=', 'low_stock_threshold')
              ->where('stock', '>', 0);
    } elseif ($request->filter == 'out_of_stock') {
        $query->where('stock', 0);
    }
    
    $books = $query->latest()->paginate(10);
    
    return view('admin.books.index', compact('books'));
}
    
    public function create()
    {
        return view('admin.books.create');
    }
    
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'author' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category' => 'required',
        'low_stock_threshold' => 'nullable|integer|min:1'
    ]);
    
    Book::create($request->all());
    
    return redirect()->route('admin.books.index')->with('success', 'Book added!');
}
    
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }
    
    public function update(Request $request, Book $book)
{
    $request->validate([
        'title' => 'required',
        'author' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category' => 'required',
        'low_stock_threshold' => 'nullable|integer|min:1'
    ]);
    
    $book->update($request->all());
    
    return redirect()->route('admin.books.index')->with('success', 'Book updated!');
}
    
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }
}