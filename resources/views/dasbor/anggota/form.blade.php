@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($anggotum) ? 'Edit Anggota' : 'Tambah Anggota Baru' }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ isset($anggotum) ? 'Perbarui informasi anggota perpustakaan.' : 'Daftarkan anggota baru ke dalam sistem.' }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8">
             <form action="{{ isset($anggotum) ? route('dasbor.anggota.update', $anggotum) : route('dasbor.anggota.store') }}" method="POST">
                @csrf
                @if(isset($anggotum))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ID Anggota -->
                    <div class="col-span-1">
                        <label for="member_id_number" class="block text-sm font-medium text-gray-700 mb-1">ID Anggota (NIS/NIP)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <input type="text" name="member_id_number" id="member_id_number" value="{{ old('member_id_number', $anggotum->member_id_number ?? '') }}" 
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg text-gray-900" 
                                placeholder="Contoh: 12345678" required>
                        </div>
                        @error('member_id_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="col-span-1">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $anggotum->full_name ?? '') }}" 
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg text-gray-900" 
                                placeholder="Nama Lengkap Anggota" required>
                        </div>
                        @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jabatan/Posisi -->
                    <div class="col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Jabatan/Posisi</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="text" name="position" id="position" value="{{ old('position', $anggotum->position ?? '') }}" 
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg text-gray-900" 
                                placeholder="Contoh: Siswa Kelas 10A" required>
                        </div>
                        @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kontak -->
                    <div class="col-span-1">
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Kontak (No. HP/Email)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <input type="text" name="contact" id="contact" value="{{ old('contact', $anggotum->contact ?? '') }}" 
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg text-gray-900" 
                                placeholder="081234567890" required>
                        </div>
                        @error('contact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if(isset($anggotum))
                    <!-- Status -->
                    <div class="col-span-1">
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status Keaktifan</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <select name="is_active" id="is_active" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg text-gray-900" required>
                                <option value="1" @if($anggotum->is_active) selected @endif>Aktif</option>
                                <option value="0" @if(!$anggotum->is_active) selected @endif>Tidak Aktif</option>
                            </select>
                        </div>
                        @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('dasbor.anggota.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                        {{ isset($anggotum) ? 'Simpan Perubahan' : 'Tambah Anggota' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
