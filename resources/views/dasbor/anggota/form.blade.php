@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ isset($member) ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ isset($member) ? route('dasbor.anggota.update', $member->id) : route('dasbor.anggota.store') }}"
                method="POST">
                @csrf
                @if (isset($member))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Siswa (NIS)</label>
                        <input type="text" name="member_id_number"
                            value="{{ old('member_id_number', $member->member_id_number ?? '') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Siswa</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $member->full_name ?? '') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <input type="text" name="position" value="{{ old('position', $member->position ?? '') }}"
                            placeholder="Contoh: 9A, 7B, atau 8E"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP / Kontak (Opsional)</label>
                        <input type="text" name="contact" value="{{ old('contact', $member->contact ?? '') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('dasbor.anggota.index') }}"
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-200">Batal</a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
