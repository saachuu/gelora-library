<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        // Fitur Pencarian
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('member_id_number', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10);

        // [BARU] Jika Request dari AJAX (Live Search), kembalikan JSON
        if ($request->ajax()) {
            return response()->json($members);
        }

        return view('dasbor.anggota.index', compact('members'));
    }

    public function create()
    {
        return view('dasbor.anggota.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id_number' => 'required|string|unique:members,member_id_number',
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $validated['is_active'] = true; // Default aktif
        Member::create($validated);

        return redirect()->route('dasbor.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Member $anggotum)
    {
        // Trik: kirim sebagai 'member' agar view tidak bingung
        return view('dasbor.anggota.form', ['member' => $anggotum]);
    }

    public function update(Request $request, Member $anggotum)
    {
        $validated = $request->validate([
            'member_id_number' => 'required|string|unique:members,member_id_number,' . $anggotum->id,
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $anggotum->update($validated);
        return redirect()->route('dasbor.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('dasbor.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
