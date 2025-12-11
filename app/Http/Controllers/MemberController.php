<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('member_id_number', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10);
        return view('dasbor.anggota.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dasbor.anggota.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id_number' => 'required|string|unique:members,member_id_number',
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        // Default status aktif saat buat baru
        $validated['is_active'] = true;

        Member::create($validated);

        return redirect()->route('dasbor.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $anggotum)
    {
        // Sesuaikan variable ke view jika view show membutuhkannya
        return view('dasbor.anggota.show', ['member' => $anggotum]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $anggotum)
    {
        // ERROR FIX: Parameter harus $anggotum (sesuai route),
        // tapi dikirim ke view sebagai 'member' agar form terisi.
        return view('dasbor.anggota.form', ['member' => $anggotum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $anggotum)
    {
        $validated = $request->validate([
            // Pengecualian unique ID menggunakan ID dari $anggotum
            'member_id_number' => 'required|string|unique:members,member_id_number,' . $anggotum->id,
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            // 'is_active' dihapus dari validasi karena tidak ada inputnya di form edit
        ]);

        $anggotum->update($validated);

        return redirect()->route('dasbor.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('dasbor.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
