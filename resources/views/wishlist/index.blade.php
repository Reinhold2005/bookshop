@extends('app')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">❤️ My Wishlist</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if($wishlistItems->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($wishlistItems as $item)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                <div class="flex">
                    <!-- Book Image/Icon -->
                    <div class="w-32 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <i class="fas fa-book-open text-4xl text-white"></i>
                    </div>
                    
                    <!-- Book Details -->
                    <div class="flex-1 p-4">
                        <h3 class="font-bold text-lg mb-1">{{ $item->book->title }}</h3>
                        <p class="text-gray-600 text-sm mb-1">by {{ $item->book->author }}</p>
                        <p class="text-gray-500 text-xs mb-2">{{ $item->book->category }}</p>
                        <p class="text-green-600 font-bold text-xl mb-3">N${{ number_format($item->book->price, 2) }}</p>
                        <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-box"></i>Instock
                        </span>
                        
                        <div class="flex gap-2">
                            <!-- Move to Cart Button -->
                            <form action="{{ route('wishlist.move-to-cart', $item) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition flex items-center justify-center gap-1">
                                    Move to Cart
                                </button>
                            </form>
                            
                            <!-- Remove Button -->
                            <form action="{{ route('wishlist.remove', $item) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition flex items-center justify-center gap-1">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Your wishlist is empty.</p>
            <a href="{{ route('books.index') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Browse Books
            </a>
        </div>
    @endif
</div>
@endsection