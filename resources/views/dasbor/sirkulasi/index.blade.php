@extends('layouts.admin')
@section('title', 'Sirkulasi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="circulationHandler()">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Sirkulasi Peminjaman dan Pengembalian</h1>
            <p class="text-gray-500 text-sm mt-1">Sistem peminjaman otomatis dengan Live Search siswa & buku.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button @click="activeTab = 'borrow'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'borrow', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'borrow' }"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-lg transition-colors duration-200">
                        📥 Peminjaman Baru
                    </button>
                    <button @click="activeTab = 'return'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'return', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'return' }"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-lg transition-colors duration-200">
                        📤 Pengembalian Buku
                    </button>
                </nav>
            </div>

            <div class="p-6 text-gray-900 bg-gray-50 min-h-[400px]">

                <div x-show="activeTab === 'borrow'" x-transition:enter="transition ease-out duration-300">
                    <form action="{{ route('dasbor.sirkulasi.pinjam') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <span
                                    class="bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Langkah
                                    1</span>
                                Cari Siswa/Anggota
                            </h3>

                            <div class="relative">
                                <input type="text" x-model="memberQuery" @input.debounce.300ms="searchMember()"
                                    placeholder="Ketik NIS atau Nama Siswa..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3"
                                    autocomplete="off">

                                <div x-show="memberResults.length > 0"
                                    class="absolute z-50 w-full mt-1 bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                    <template x-for="member in memberResults" :key="member.id">
                                        <div @click="selectMember(member)"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white border-b border-gray-50">
                                            <div class="flex justify-between items-center px-2">
                                                <span x-text="member.text" class="font-medium block truncate"></span>
                                                <span x-show="member.has_loan"
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Sedang Meminjam
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div x-show="selectedMember" class="mt-4 p-4 bg-indigo-50 rounded-md border border-indigo-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm text-indigo-700 font-bold">Siswa Terpilih:</p>
                                        <p class="text-lg font-bold text-indigo-900" x-text="selectedMember?.text"></p>
                                        <input type="hidden" name="member_id" :value="selectedMember?.id">
                                    </div>
                                    <button type="button" @click="resetMember()"
                                        class="text-red-600 hover:text-red-800 text-sm underline font-medium">Ganti
                                        Siswa</button>
                                </div>

                                <div x-show="selectedMember?.has_loan"
                                    class="mt-3 p-3 bg-red-100 text-red-700 rounded text-sm border border-red-200 flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <b>AKSES DITOLAK:</b> Siswa ini masih meminjam buku
                                        <span class="font-bold underline"
                                            x-text="selectedMember?.loan_details?.book_title"></span>.
                                        <br>Harap kembalikan buku tersebut sebelum meminjam buku baru.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedMember && !selectedMember.has_loan"
                            class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <span
                                    class="bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Langkah
                                    2</span>
                                Cari Buku
                            </h3>

                            <div class="relative">
                                <input type="text" x-model="bookQuery" @input.debounce.300ms="searchBook()"
                                    placeholder="Ketik ISBN atau Judul Buku..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3"
                                    autocomplete="off">

                                <div x-show="bookResults.length > 0"
                                    class="absolute z-50 w-full mt-1 bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                    <template x-for="book in bookResults" :key="book.id">
                                        <div @click="selectBook(book)"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white border-b border-gray-50">
                                            <span x-text="book.text" class="block truncate px-2"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div x-show="selectedBook" class="mt-4 p-4 bg-green-50 rounded-md border border-green-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm text-green-700 font-bold">Buku Terpilih:</p>
                                        <p class="text-lg font-bold text-green-900" x-text="selectedBook?.text"></p>
                                        <input type="hidden" name="book_id" :value="selectedBook?.id">
                                    </div>
                                    <button type="button" @click="resetBook()"
                                        class="text-red-600 hover:text-red-800 text-sm underline font-medium">Ganti
                                        Buku</button>
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedMember && selectedBook && !selectedMember.has_loan"
                            class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg flex items-center text-lg w-full justify-center sm:w-auto">
                                <span>Proses Peminjaman</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div x-show="activeTab === 'return'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300">
                    <form action="{{ route('dasbor.sirkulasi.kembali') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 text-center py-12">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Siapa yang mengembalikan buku?</h3>
                            <p class="text-gray-500 mb-8 text-sm">Cukup cari nama siswa, sistem akan otomatis mendeteksi
                                buku yang dipinjam.</p>

                            <div class="max-w-xl mx-auto relative">
                                <input type="text" x-model="memberQuery" @input.debounce.300ms="searchMember()"
                                    placeholder="Ketik NIS atau Nama Siswa..."
                                    class="w-full text-center text-lg py-4 rounded-full border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    autocomplete="off">

                                <div x-show="memberResults.length > 0"
                                    class="absolute z-50 w-full mt-1 bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto text-left sm:text-sm">
                                    <template x-for="member in memberResults" :key="member.id">
                                        <div @click="selectMemberForReturn(member)"
                                            class="cursor-pointer select-none relative py-3 pl-4 pr-4 hover:bg-indigo-600 hover:text-white border-b border-gray-50 flex justify-between">
                                            <span x-text="member.text" class="font-medium"></span>
                                            <span x-text="member.has_loan ? '🟢 Ada Pinjaman' : '⚪ Tidak Ada'"
                                                class="text-xs opacity-75 py-1 px-2 bg-gray-200 rounded text-gray-800"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div x-show="selectedReturnMember"
                                class="mt-8 text-left max-w-2xl mx-auto bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">

                                <div class="bg-indigo-50 p-4 border-b border-indigo-100 flex justify-between items-center">
                                    <span class="font-bold text-indigo-900" x-text="selectedReturnMember?.text"></span>
                                    <button type="button" @click="selectedReturnMember = null; memberQuery = '';"
                                        class="text-xs text-red-500 hover:text-red-700 font-bold uppercase">Batalkan</button>
                                </div>

                                <div class="p-6">
                                    <div x-show="!selectedReturnMember?.has_loan" class="text-center py-8 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p>Siswa ini tidak memiliki pinjaman aktif.</p>
                                    </div>

                                    <div x-show="selectedReturnMember?.has_loan">
                                        <div class="flex items-start space-x-4 mb-6">
                                            <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-bold text-gray-900"
                                                    x-text="selectedReturnMember?.loan_details?.book_title"></h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Jatuh Tempo: <span
                                                        x-text="selectedReturnMember?.loan_details?.due_date"
                                                        class="font-medium"></span>
                                                    <span x-show="selectedReturnMember?.loan_details?.is_overdue"
                                                        class="ml-2 text-white font-bold bg-red-600 px-2 py-0.5 rounded text-xs animate-pulse">TERLAMBAT</span>
                                                </p>
                                            </div>
                                        </div>

                                        <input type="hidden" name="member_id" :value="selectedReturnMember?.id">

                                        <button type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg transition shadow-lg text-lg">
                                            ✅ Konfirmasi Pengembalian Buku
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function circulationHandler() {
            return {
                activeTab: 'borrow', // borrow | return

                // Data Pencarian Member
                memberQuery: '',
                memberResults: [],
                selectedMember: null,

                // Data Pencarian Buku
                bookQuery: '',
                bookResults: [],
                selectedBook: null,

                // Data Khusus Return
                selectedReturnMember: null,

                // --- SEARCH MEMBER ---
                async searchMember() {
                    if (this.memberQuery.length < 1) {
                        this.memberResults = [];
                        return;
                    }
                    try {
                        // Panggil API Laravel
                        let response = await fetch(`{{ route('api.members.search') }}?q=${this.memberQuery}`);
                        this.memberResults = await response.json();
                    } catch (error) {
                        console.error('Error fetching members:', error);
                    }
                },

                // --- PILIH MEMBER (BORROW MODE) ---
                selectMember(member) {
                    this.selectedMember = member;
                    this.memberQuery = ''; // Clear input
                    this.memberResults = []; // Clear dropdown
                },

                resetMember() {
                    this.selectedMember = null;
                    this.selectedBook = null;
                    this.bookQuery = '';
                },

                // --- SEARCH BUKU ---
                async searchBook() {
                    if (this.bookQuery.length < 2) {
                        this.bookResults = [];
                        return;
                    }
                    try {
                        let response = await fetch(`{{ route('api.books.search') }}?q=${this.bookQuery}`);
                        this.bookResults = await response.json();
                    } catch (error) {
                        console.error('Error fetching books:', error);
                    }
                },

                selectBook(book) {
                    this.selectedBook = book;
                    this.bookQuery = '';
                    this.bookResults = [];
                },

                resetBook() {
                    this.selectedBook = null;
                },

                // --- PILIH MEMBER (RETURN MODE) ---
                selectMemberForReturn(member) {
                    this.selectedReturnMember = member;
                    this.memberQuery = '';
                    this.memberResults = [];
                }
            }
        }
    </script>
@endsection
