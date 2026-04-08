@extends('app')

@section('title', 'Welcome')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center text-white">
            <h1 class="text-5xl font-bold mb-4">📚 Welcome to Tales & Tomes Bookshop</h1>
            <p class="text-xl mb-8">Your favorite online bookstore. Discover thousands of books at amazing prices.</p>
            
            @auth
                <a href="{{ route('books.index') }}" 
                   class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Browse Books
                </a>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" 
                       class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        Register
                    </a>
                </div>
            @endauth
        </div>
        
        <!-- Featured Books Section -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Featured Books</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $featuredBooks = App\Models\Book::take(4)->get();
                @endphp
                @foreach($featuredBooks as $book)
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-bold text-lg">{{ $book->title }}</h3>
                    <p class="text-gray-600">by {{ $book->author }}</p>
                    <p class="text-green-600 font-bold mt-2">${{ number_format($book->price, 2) }}</p>
                    <a href="{{ route('books.show', $book) }}" class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded text-center">
                        View Details
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection