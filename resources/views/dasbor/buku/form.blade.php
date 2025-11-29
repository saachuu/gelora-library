@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">
        {{ isset($buku) ? 'Edit Buku' : 'Tambah Buku Baru' }}
    </h1>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ isset($buku) ? route('dasbor.buku.update', $buku) : route('dasbor.buku.store') }}" method="POST">
                @csrf
                @if(isset($buku))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Judul -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $buku->title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                     <!-- Penulis -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700">Penulis</label>
                        <input type="text" name="author" id="author" value="{{ old('author', $buku->author ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('author') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if(isset($buku) && $buku->category_id == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $buku->isbn ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('isbn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                     <!-- Penerbit -->
                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-700">Penerbit</label>
                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $buku->publisher ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('publisher') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun Terbit -->
                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                        <input type="number" name="publication_year" id="publication_year" value="{{ old('publication_year', $buku->publication_year ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('publication_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi Rak -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Lokasi Rak</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $buku->location ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                     <!-- Stok -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $buku->stock ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                        @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if(isset($buku))
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900" required>
                            <option value="Tersedia" @if($buku->status == 'Tersedia') selected @endif>Tersedia</option>
                            <option value="Rusak" @if($buku->status == 'Rusak') selected @endif>Rusak</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-900">{{ old('description', $buku->description ?? '') }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('dasbor.buku.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 mr-2">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        {{ isset($buku) ? 'Perbarui Buku' : 'Simpan Buku' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
