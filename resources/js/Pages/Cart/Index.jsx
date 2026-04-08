 import { Head, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Cart({ cart }) {
    const removeFromCart = (cartId) => {
        router.delete(`/cart/remove/${cartId}`);
    };

    const moveToWishlist = (item) => {
        router.post(`/wishlist/add/${item.book_id}`);
        router.delete(`/cart/remove/${item.id}`);
    };

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    return (
        <AuthenticatedLayout>
            <Head title="Shopping Cart" />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold mb-6">Shopping Cart</h1>
                    
                    {cart.length === 0 ? (
                        <p>Your cart is empty. <Link href="/books" className="text-blue-500">Continue shopping</Link></p>
                    ) : (
                        <>
                            {cart.map((item) => (
                                <div key={item.id} className="bg-white rounded-lg shadow p-4 mb-4 flex justify-between">
                                    <div>
                                        <h3 className="font-bold">{item.title}</h3>
                                        <p>Quantity: {item.quantity}</p>
                                        <p>${item.price} each</p>
                                    </div>
                                    <div className="space-x-2">
                                        <button 
                                            onClick={() => moveToWishlist(item)}
                                            className="bg-yellow-500 text-white px-4 py-2 rounded"
                                        >
                                            Move to Wishlist
                                        </button>
                                        <button 
                                            onClick={() => removeFromCart(item.id)}
                                            className="bg-red-500 text-white px-4 py-2 rounded"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            ))}
                            
                            <div className="bg-white rounded-lg shadow p-4 mt-6">
                                <h2 className="text-xl font-bold">Total: ${total.toFixed(2)}</h2>
                                <button className="mt-4 bg-blue-500 text-white px-6 py-2 rounded w-full">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
