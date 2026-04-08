@extends('app')

@section('title', 'Payment Successful')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 text-center">
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-green-500 text-6xl mb-4">✓</div>
        <h1 class="text-3xl font-bold mb-4">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your purchase. Your order has been confirmed.</p>
        <a href="{{ route('books.index') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Continue Shopping...
        </a>
    </div>
</div>
@endsection