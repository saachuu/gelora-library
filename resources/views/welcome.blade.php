<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Katalog Perpustakaan</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <nav class="flex justify-between items-center mb-8">
                 <h1 class="text-3xl font-bold text-indigo-600">Katalog Buku</h1>
                 <div>
                    @auth
                        <a href="{{ url('/dasbor') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
                        @endif
                    @endauth
                </div>
            </nav>

            <!-- Search Bar -->
            <div class="mb-8">
                <form action="{{ route('welcome') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari berdasarkan judul, penulis, atau kategori..."
                           class="w-full px-4 py-2 border rounded-md" value="{{ request('search') }}">
                </form>
            </div>


            <!-- Book Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($books as $book)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ $book->cover_image_url ?? 'https://via.placeholder.com/300x400.png?text=No+Cover' }}" alt="Cover Buku" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <span class="text-sm text-gray-500">{{ $book->category->name }}</span>
                            <h2 class="text-lg font-bold mt-1">{{ $book->title }}</h2>
                            <p class="text-sm text-gray-600">{{ $book->author }}</p>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('buku.show', $book) }}" class="text-indigo-600 hover:text-indigo-800">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                     <p class="col-span-full text-center text-gray-500">Tidak ada buku yang ditemukan.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $books->links() }}
            </div>

        </div>
    </body>
</html>