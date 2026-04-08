 import { Head, router, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Wishlist({ wishlist }) {
    const removeFromWishlist = (wishlistId) => {
        router.delete(`/wishlist/remove/${wishlistId}`);
    };

    const moveToCart = (bookId) => {
        router.post(`/cart/add/${bookId}`);
        router.delete(`/wishlist/remove/${wishlistId}`);
    };

    return (
        <AuthenticatedLayout>
            <Head title="My Wishlist" />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold mb-6">My Wishlist</h1>
                    
                    {wishlist.length === 0 ? (
                        <p>Your wishlist is empty. <Link href="/books" className="text-blue-500">Browse books</Link></p>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {wishlist.map((item) => (
                                <div key={item.id} className="bg-white rounded-lg shadow p-4">
                                    <h3 className="font-bold text-lg">{item.book.title}</h3>
                                    <p className="text-gray-600">by {item.book.author}</p>
                                    <p className="text-green-600 font-bold">${item.book.price}</p>
                                    <div className="mt-4 space-x-2">
                                        <button 
                                            onClick={() => moveToCart(item.book.id, item.id)}
                                            className="bg-green-500 text-white px-4 py-2 rounded"
                                        >
                                            Move to Cart
                                        </button>
                                        <button 
                                            onClick={() => removeFromWishlist(item.id)}
                                            className="bg-red-500 text-white px-4 py-2 rounded"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
