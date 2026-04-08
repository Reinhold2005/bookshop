@extends('admin.layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Edit Book</h1>

    <form action="{{ route('admin.books.update', $book) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Title</label>
            <input type="text" name="title" value="{{ $book->title }}" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Author</label>
            <input type="text" name="author" value="{{ $book->author }}" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" required class="w-full px-3 py-2 border rounded">{{ $book->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Price</label>
            <input type="number" name="price" step="0.01" value="{{ $book->price }}" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Stock</label>
            <input type="number" name="stock" value="{{ $book->stock }}" required class="w-full px-3 py-2 border rounded">
        </div>

         <div class="mb-4">
    <label class="block text-gray-700 mb-2">Low Stock Threshold</label>
    <input type="number" name="low_stock_threshold" value="{{ $book->low_stock_threshold }}" 
           class="w-full px-3 py-2 border rounded">
    <p class="text-xs text-gray-500 mt-1">Alert when stock reaches or falls below this number</p>
</div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Category</label>
            <input type="text" name="category" value="{{ $book->category }}" required class="w-full px-3 py-2 border rounded">
        </div>

       

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Update Book</button>
        <a href="{{ route('admin.books.index') }}" class="ml-2 text-gray-500 hover:underline">Cancel</a>
    </form>
</div>
@endsection