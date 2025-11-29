<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $book->title }} - Detail Buku</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100">
        <div class="container mx-auto px-4 py-8">
             <div class="mb-4">
                <a href="{{ route('welcome') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali ke Katalog</a>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <img src="{{ $book->cover_image_url ?? 'https://via.placeholder.com/300x400.png?text=No+Cover' }}" alt="Cover Buku" class="w-full h-auto object-cover rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full">{{ $book->category->name }}</span>
                        <h1 class="text-4xl font-bold mt-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-700 mt-1">oleh {{ $book->author }}</p>

                        <div class="mt-6 border-t pt-4">
                            <h2 class="text-lg font-semibold">Detail Buku</h2>
                            <ul class="mt-2 space-y-2 text-gray-600">
                                <li><strong>Penerbit:</strong> {{ $book->publisher }}</li>
                                <li><strong>Tahun Terbit:</strong> {{ $book->publication_year }}</li>
                                <li><strong>ISBN:</strong> {{ $book->isbn }}</li>
                                <li><strong>Lokasi Rak:</strong> {{ $book->location }}</li>
                                <li><strong>Stok Tersedia:</strong> <span class="font-bold {{ $book->available > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $book->available }}</span></li>
                            </ul>
                        </div>

                        @if($book->description)
                            <div class="mt-6 border-t pt-4">
                                <h2 class="text-lg font-semibold">Deskripsi</h2>
                                <p class="mt-2 text-gray-600">{{ $book->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
