@extends('admin.layouts.app')

@section('title', 'Manage Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📚 Manage Books</h1>
        <a href="{{ route('admin.books.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            + Add New Book
        </a>
    </div>

    <!-- Filter Buttons -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.books.index') }}" 
       class="px-4 py-2 rounded {{ !request('filter') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        All Books
    </a>
    <a href="{{ route('admin.books.index', ['filter' => 'low_stock']) }}" 
       class="px-4 py-2 rounded {{ request('filter') == 'low_stock' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        ⚠️ Low Stock
    </a>
    <a href="{{ route('admin.books.index', ['filter' => 'out_of_stock']) }}" 
       class="px-4 py-2 rounded {{ request('filter') == 'out_of_stock' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
        ❌ Out of Stock
    </a>
</div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Author</th>
                    <th class="px-6 py-3 text-left">Price</th>
                    <th class="px-6 py-3 text-left">Stock</th>
                    <th class="px-6 py-3 text-left">Sold</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $book->id }}</td>
                    <td class="px-6 py-4">{{ $book->title }}</td>
                    <td class="px-6 py-4">{{ $book->author }}</td>
                    <td class="px-6 py-4">N${{ number_format($book->price, 2) }}</td>
                    <td class="px-6 py-4">
    @if($book->stock == 0)
        <span class="px-2 py-1 rounded text-xs bg-red-500 text-white">
            Out of Stock
        </span>
    @elseif($book->stock <= $book->low_stock_threshold)
        <span class="px-2 py-1 rounded text-xs bg-yellow-500 text-white">
            Low Stock ({{ $book->stock }})
        </span>
    @else
        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
            In Stock ({{ $book->stock }})
        </span>
    @endif
</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold {{ $book->sold_count > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                            {{ $book->sold_count }}
                        </span>
                        @if($book->sold_count > 0)
                            <span class="text-xs text-gray-500 block">copies sold</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-500 hover:underline">Edit</a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Delete this book?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection