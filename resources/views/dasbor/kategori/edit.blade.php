@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Edit Kategori</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui nama kategori buku.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('dasbor.kategori.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg text-gray-900" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('dasbor.kategori.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
