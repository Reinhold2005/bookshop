 import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ books }) {
    const [search, setSearch] = useState('');

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/books', { search: search });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Books" />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Search Bar */}
                    <form onSubmit={handleSearch} className="mb-6">
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search by title or author..."
                            className="w-full px-4 py-2 border rounded-lg"
                        />
                        <button type="submit" className="mt-2 px-4 py-2 bg-blue-500 text-white rounded">
                            Search
                        </button>
                    </form>

                    {/* Books Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        {books.data.map((book) => (
                            <div key={book.id} className="bg-white rounded-lg shadow p-4">
                                <h3 className="font-bold text-lg">{book.title}</h3>
                                <p className="text-gray-600">by {book.author}</p>
                                <p className="text-green-600 font-bold mt-2">${book.price}</p>
                                <Link 
                                    href={`/books/${book.id}`}
                                    className="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded"
                                >
                                    View Details
                                </Link>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
