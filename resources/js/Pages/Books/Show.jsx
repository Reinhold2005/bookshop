 import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Show({ book }) {
    const addToCart = () => {
        router.post(`/cart/add/${book.id}`);
    };

    const addToWishlist = () => {
        router.post(`/wishlist/add/${book.id}`);
    };

    return (
        <AuthenticatedLayout>
            <Head title={book.title} />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white rounded-lg shadow p-6">
                        <h1 className="text-3xl font-bold mb-4">{book.title}</h1>
                        <p className="text-xl text-gray-700 mb-2">By {book.author}</p>
                        <p className="text-gray-600 mb-4">{book.description}</p>
                        <p className="text-2xl text-green-600 font-bold mb-4">${book.price}</p>
                        <p className="mb-4">Stock: {book.stock} copies</p>
                        
                        <div className="space-x-4">
                            <button 
                                onClick={addToCart}
                                className="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600"
                            >
                                Add to Cart
                            </button>
                            <button 
                                onClick={addToWishlist}
                                className="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600"
                            >
                                Add to Wishlist
                            </button>
                            <Link 
                                href="/books"
                                className="bg-gray-500 text-white px-6 py-2 rounded inline-block"
                            >
                                Back to Books
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
