<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class VisitController extends Controller
{
    public function index()
    {
        $todayVisits = Visit::with('member')
            ->whereDate('check_in_at', Carbon::today())
            ->latest('updated_at')
            ->paginate(10);

        return view('dasbor.absensi.index', compact('todayVisits'));
    }

    // API Cek Status
    public function checkStatus($memberId)
    {
        $activeVisit = Visit::where('member_id', $memberId)
            ->whereDate('check_in_at', Carbon::today())
            ->whereNull('check_out_at')
            ->first();

        return response()->json([
            'is_checked_in' => $activeVisit ? true : false,
            'check_in_time' => $activeVisit ? Carbon::parse($activeVisit->check_in_at)->format('H:i') : null,
        ]);
    }

    // API Live Search
    public function searchMember(Request $request)
    {
        $search = $request->q;
        if (empty($search)) return response()->json([]);

        $members = Member::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('member_id_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get();

        $results = $members->map(function($member) {
            return [
                'id' => $member->id,
                'text' => $member->member_id_number . ' - ' . $member->full_name,
                'name' => $member->full_name
            ];
        });

        return response()->json($results);
    }

    // Proses Simpan
   public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'notes' => 'nullable|string',
        ]);

        $member = Member::find($request->member_id);

        // Cek apakah sedang berkunjung (Masuk hari ini, belum keluar)
        $activeVisit = Visit::where('member_id', $member->id)
            ->whereDate('check_in_at', Carbon::today())
            ->whereNull('check_out_at')
            ->first();

        if ($activeVisit) {
            // === CHECK-OUT (KELUAR) ===
            $checkOutTime = now();

            // PENTING: Gunakan Carbon::parse untuk keamanan perhitungan durasi
            $checkInTime = Carbon::parse($activeVisit->check_in_at);
            $duration = $checkInTime->diffInMinutes($checkOutTime);

            $gotPoint = $duration > 10;

            $activeVisit->update([
                'check_out_at' => $checkOutTime,
                'duration_minutes' => $duration,
                'got_point' => $gotPoint
            ]);

            $msg = "Sampai jumpa, {$member->full_name}. Durasi: {$duration} menit.";
            if ($gotPoint) $msg .= " (+1 Poin)";

            return back()->with('success', $msg);

        } else {
            // === CHECK-IN (MASUK) ===
            Visit::create([
                'member_id' => $member->id,
                'check_in_at' => now(),
                'notes' => $request->notes, // Ini sekarang aman karena sudah ada di fillable
                'got_point' => false,
            ]);

            return back()->with('success', "Selamat datang, {$member->full_name}!");
        }
    }

    public function leaderboard()
    {
        // Hitung total poin (jumlah kunjungan yang got_point = true)
        $top_students = Member::withCount(['visits as total_points' => function ($query) {
                $query->where('got_point', true);
            }])
            ->orderByDesc('total_points')
            ->limit(20)
            ->get();

        return view('dasbor.absensi.leaderboard', compact('top_students'));
    }

    // --- FITUR EXPORT PDF ---

    // 1. Export Harian (Perbaikan Error)
    public function exportPdf()
    {
        $visits = Visit::with('member')
            ->whereDate('check_in_at', Carbon::today())
            ->orderBy('check_in_at', 'asc') // Urutkan dari yang datang duluan
            ->get();

        $pdf = Pdf::loadView('dasbor.absensi.pdf', compact('visits'));
        // Set ukuran kertas F4 atau A4 landscape agar muat banyak kolom
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Kunjungan.pdf');
    }

    // 2. Export Leaderboard (Fitur Baru)
    public function exportLeaderboardPdf()
    {
        $members = Member::withCount(['visits as total_points' => function ($query) {
                $query->where('got_point', true);
            }])
            ->having('total_points', '>', 0) // Hanya yg punya poin
            ->orderByDesc('total_points')
            ->limit(50)
            ->get();

        $pdf = Pdf::loadView('dasbor.absensi.pdf_leaderboard', compact('members'));
        return $pdf->download('Peringkat-Siswa-Rajin.pdf');
    }
}
