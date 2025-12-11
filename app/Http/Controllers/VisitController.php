<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitController extends Controller
{
    public function index()
    {
        $todayVisits = Visit::with('member')
            ->whereDate('created_at', today())
            ->latest('updated_at')
            ->paginate(10);

        return view('dasbor.absensi.index', compact('todayVisits'));
    }

    /**
     * API: Cek Status Member (Sedang berkunjung atau tidak?)
     */
    public function checkStatus($memberId)
    {
        $activeVisit = Visit::where('member_id', $memberId)
            ->whereDate('created_at', today())
            ->whereNull('check_out_at')
            ->first();

        return response()->json([
            'is_checked_in' => $activeVisit ? true : false,
            'check_in_time' => $activeVisit ? $activeVisit->check_in_at->format('H:i') : null,
        ]);
    }

    /**
     * API: Live Search Khusus Absensi
     */
    public function searchMember(Request $request)
    {
        $search = $request->get('q');

        $members = Member::where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('member_id_number', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $results = $members->map(function($member) {
            return [
                'id' => $member->id,
                'text' => $member->member_id_number . ' - ' . $member->full_name,
            ];
        });

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $member = Member::find($request->member_id);

        // Cek apakah sedang berkunjung
        $activeVisit = Visit::where('member_id', $member->id)
            ->whereDate('created_at', today())
            ->whereNull('check_out_at')
            ->first();

        if ($activeVisit) {
            // === CHECK-OUT ===
            $checkOutTime = now();
            $duration = $activeVisit->check_in_at->diffInMinutes($checkOutTime);
            $gotPoint = $duration >= 10;

            $activeVisit->update([
                'check_out_at' => $checkOutTime,
                'duration_minutes' => $duration,
                'got_point' => $gotPoint
            ]);

            $msg = "Sampai jumpa, {$member->full_name}. Durasi: {$duration} menit.";
            if ($gotPoint) $msg .= " (+1 Poin)";

            return back()->with('success', $msg);

        } else {
            // === CHECK-IN ===
            Visit::create([
                'member_id' => $member->id,
                'check_in_at' => now(),
            ]);

            return back()->with('success', "Selamat datang, {$member->full_name}!");
        }
    }

    /**
     * Menampilkan Halaman Peringkat (Leaderboard)
     */
    public function leaderboard()
    {
        // Ambil data member, hitung jumlah visit yang 'got_point' = true
        $members = Member::withCount(['visits as total_points' => function ($query) {
                $query->where('got_point', true);
            }])
            ->orderByDesc('total_points') // Urutkan dari poin terbanyak
            ->orderBy('full_name')        // Jika poin sama, urut abjad
            ->paginate(20);               // Tampilkan 20 siswa per halaman

        return view('dasbor.absensi.leaderboard', compact('members'));
    }

    /**
     * Cetak Laporan PDF
     */
    public function exportPdf()
    {
        // Ambil 50 siswa ter-rajin
        $members = Member::withCount(['visits as total_points' => function ($query) {
                $query->where('got_point', true);
            }])
            ->orderByDesc('total_points')
            ->orderBy('full_name')
            ->limit(50) // Batasi 50 agar muat di kertas
            ->get();

        // Load view khusus PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dasbor.absensi.pdf', compact('members'));

        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Download file
        return $pdf->stream('Laporan-Keaktifan-Siswa.pdf');
    }
}
