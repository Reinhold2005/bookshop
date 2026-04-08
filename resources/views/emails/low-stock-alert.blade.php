<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; }
        .book-item { border-bottom: 1px solid #ddd; padding: 10px; }
        .low-stock { color: #f59e0b; }
        .out-of-stock { color: #ef4444; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Low Stock Alert</h1>
        </div>
        
        @if(count($outOfStockBooks) > 0)
        <h2>Out of Stock Books</h2>
        @foreach($outOfStockBooks as $book)
        <div class="book-item out-of-stock">
            <strong>{{ $book->title }}</strong> by {{ $book->author }}
            <span class="out-of-stock">- OUT OF STOCK</span>
        </div>
        @endforeach
        @endif
        
        @if(count($lowStockBooks) > 0)
        <h2>Low Stock Books</h2>
        @foreach($lowStockBooks as $book)
        <div class="book-item low-stock">
            <strong>{{ $book->title }}</strong> by {{ $book->author }}
            <span class="low-stock">- Only {{ $book->stock }} left</span>
        </div>
        @endforeach
        @endif
        
        <p style="margin-top: 20px;">
            <a href="{{ route('admin.books.index', ['filter' => 'low_stock']) }}" style="background: #f59e0b; color: white; padding: 10px 20px; text-decoration: none;">
                View Low Stock Books
            </a>
        </p>
    </div>
</body>
</html>