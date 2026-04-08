@extends('app')

@section('title', $book->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('books.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">← Back to Books</a>
    
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-4">{{ $book->title }}</h1>
        <p class="text-xl text-gray-700 mb-2">by {{ $book->author }}</p>
        <p class="text-gray-600 mb-4">
            <span class="bg-gray-200 px-2 py-1 rounded">{{ $book->category }}</span>
        </p>
        
       <div class="border-t border-b py-4 my-4">
            <p class="text-gray-700 leading-relaxed">{{ $book->description ?? 'No description available.' }}</p>
        </div> 

        <!-- Rating Section -->
<div class="mb-4">
    <div class="flex items-center gap-2 mb-2">
        <div class="flex items-center">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-2xl {{ $i <= round($book->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
            @endfor
        </div>
        <span class="text-gray-600">({{ $book->total_ratings }} reviews)</span>
    </div>
    
    <div class="mt-3">
        <label class="block text-gray-700 mb-2">Rate this book:</label>
        <select onchange="rateBook({{ $book->id }}, this.value)" 
                class="px-4 py-2 border rounded-lg">
            <option value="">Select rating...</option>
            <option value="5" {{ ($book->getUserRatingAttribute() == 5) ? 'selected' : '' }}>★★★★★ (5 - Excellent)</option>
            <option value="4" {{ ($book->getUserRatingAttribute() == 4) ? 'selected' : '' }}>★★★★☆ (4 - Very Good)</option>
            <option value="3" {{ ($book->getUserRatingAttribute() == 3) ? 'selected' : '' }}>★★★☆☆ (3 - Good)</option>
            <option value="2" {{ ($book->getUserRatingAttribute() == 2) ? 'selected' : '' }}>★★☆☆☆ (2 - Fair)</option>
            <option value="1" {{ ($book->getUserRatingAttribute() == 1) ? 'selected' : '' }}>★☆☆☆☆ (1 - Poor)</option>
        </select>
    </div>
</div>
        
        <div class="mt-4">
            <p class="text-3xl text-green-600 font-bold mb-2">N${{ number_format($book->price, 2) }}</p>
            <p class="text-gray-600 mb-4">📚 Instock</p>
            
            <div class="flex gap-3">
                <form action="{{ route('cart.add', $book) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                        🛒 Add to Cart
                    </button>
                </form>
                
                <form action="{{ route('wishlist.add', $book) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">
                        ❤️ Add to Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection