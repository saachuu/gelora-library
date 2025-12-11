@extends('layouts.admin')
@section('title', 'Daftar Buku')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showModal: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Buku</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola koleksi buku perpustakaan Anda.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-64 sm:text-sm text-gray-900"
                        placeholder="Cari judul, penulis, ISBN...">
                </div>

                <button @click="showModal = true" type="button"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Import Excel
                </button>

                <a href="{{ route('dasbor.buku.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Buku
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($books as $book)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-lg">
                                            {{ substr($book->title, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 line-clamp-1"
                                                title="{{ $book->title }}">{{ $book->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $book->author }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">ISBN: {{ $book->isbn }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $book->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="text-sm font-semibold {{ $book->available > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $book->available }} / {{ $book->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('dasbor.buku.edit', $book->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded"
                                            title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg></a>
                                        <form action="{{ route('dasbor.buku.destroy', $book->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Hapus buku ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $books->links() }}</div>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>
                <div
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Import Data Buku</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4">Upload Excel (.xlsx) dengan kolom: <b>judul, penulis, isbn,
                                stok, kategori, rak</b>.</p>
                        <form action="{{ route('dasbor.buku.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                required>
                            <div class="mt-4 flex justify-end">
                                <button type="button" @click="showModal = false"
                                    class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('dasbor.buku.index') }}",
                    type: "GET",
                    data: {
                        'q': query
                    },
                    success: function(data) {
                        var rows = '';
                        var books = data.data;

                        if (books.length > 0) {
                            $.each(books, function(index, book) {
                                var editUrl = "{{ url('dasbor/buku') }}/" + book.id +
                                    "/edit";
                                var deleteUrl = "{{ url('dasbor/buku') }}/" + book.id;
                                var csrf = "{{ csrf_token() }}";
                                var firstLetter = book.title.substring(0, 1);
                                var categoryName = book.category ? book.category.name :
                                    'Umum';
                                var stockColor = book.available > 0 ? 'text-green-600' :
                                    'text-red-600';

                                rows +=
                                    '<tr class="hover:bg-gray-50 transition-colors">';
                                rows +=
                                    '<td class="px-6 py-4"><div class="flex items-start"><div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-lg">' +
                                    firstLetter +
                                    '</div><div class="ml-4"><div class="text-sm font-medium text-gray-900 line-clamp-1">' +
                                    book.title +
                                    '</div><div class="text-sm text-gray-500">' + book
                                    .author +
                                    '</div><div class="text-xs text-gray-400 mt-0.5">ISBN: ' +
                                    (book.isbn || '-') + '</div></div></div></td>';
                                rows +=
                                    '<td class="px-6 py-4 whitespace-nowrap"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' +
                                    categoryName + '</span></td>';
                                rows +=
                                    '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' +
                                    (book.location || '-') + '</td>';
                                rows +=
                                    '<td class="px-6 py-4 whitespace-nowrap text-center"><span class="text-sm font-semibold ' +
                                    stockColor + '">' + book.available + ' / ' + book
                                    .stock + '</span></td>';
                                rows +=
                                    '<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><div class="flex justify-end space-x-2">';
                                rows += '<a href="' + editUrl +
                                    '" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>';
                                rows += '<form action="' + deleteUrl +
                                    '" method="POST" class="inline-block" onsubmit="return confirm(\'Hapus buku?\');"><input type="hidden" name="_token" value="' +
                                    csrf +
                                    '"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>';
                                rows += '</div></td></tr>';
                            });
                        } else {
                            rows =
                                '<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Buku tidak ditemukan.</td></tr>';
                        }
                        $('#tableBody').html(rows);
                    }
                });
            });
        });
    </script>
@endsection
