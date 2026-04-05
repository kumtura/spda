<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiCounter;
use App\Models\TicketCounterAssignment;
use App\Models\TiketWisata;
use App\Models\ObjekWisata;
use Carbon\Carbon;

class TicketCounterController extends Controller
{
    /**
     * Get the assigned objek wisata IDs for the current ticket counter user
     */
    private function getAssignedObjekIds()
    {
        return TicketCounterAssignment::where('id_user', auth()->id())
            ->where('aktif', '1')
            ->pluck('id_objek_wisata');
    }

    /**
     * Get active shift (clock-in without clock-out) for today
     */
    private function getActiveShift()
    {
        return AbsensiCounter::where('id_user', auth()->id())
            ->whereNull('waktu_keluar')
            ->latest('waktu_masuk')
            ->first();
    }

    /**
     * Dashboard home for ticket counter
     */
    public function index()
    {
        $assignedIds = $this->getAssignedObjekIds();
        $activeShift = $this->getActiveShift();

        $objekWisata = ObjekWisata::whereIn('id_objek_wisata', $assignedIds)
            ->where('aktif', '1')
            ->where('status', 'aktif')
            ->get();

        // Today's sales for assigned objects
        $tiketHariIni = TiketWisata::whereDate('created_at', today())
            ->where('status_pembayaran', 'completed')
            ->whereIn('id_objek_wisata', $assignedIds)
            ->with('details')
            ->get();

        $totalPenjualanHariIni = $tiketHariIni->sum('total_harga');
        $totalTiketTerjual = $tiketHariIni->sum(fn($t) => $t->details->sum('jumlah'));

        // Sales during active shift only
        $penjualanShift = 0;
        $tiketShift = 0;
        if ($activeShift) {
            $tiketDuringShift = TiketWisata::where('created_at', '>=', $activeShift->waktu_masuk)
                ->where('status_pembayaran', 'completed')
                ->where('petugas_scan', auth()->id())
                ->whereIn('id_objek_wisata', $assignedIds)
                ->with('details')
                ->get();
            $penjualanShift = $tiketDuringShift->sum('total_harga');
            $tiketShift = $tiketDuringShift->sum(fn($t) => $t->details->sum('jumlah'));
        }

        return view('backend.ticketcounter.home', compact(
            'objekWisata', 'activeShift', 'tiketHariIni',
            'totalPenjualanHariIni', 'totalTiketTerjual',
            'penjualanShift', 'tiketShift'
        ));
    }

    /**
     * Absensi page
     */
    public function absensi()
    {
        $activeShift = $this->getActiveShift();
        $assignedIds = $this->getAssignedObjekIds();

        $objekWisata = ObjekWisata::whereIn('id_objek_wisata', $assignedIds)
            ->where('aktif', '1')
            ->get();

        // Riwayat absensi bulan ini
        $riwayat = AbsensiCounter::where('id_user', auth()->id())
            ->whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->with('objekWisata')
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('backend.ticketcounter.absensi', compact('activeShift', 'objekWisata', 'riwayat'));
    }

    /**
     * Clock in
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
        ]);

        // Verify user is assigned to this objek wisata
        $isAssigned = TicketCounterAssignment::where('id_user', auth()->id())
            ->where('id_objek_wisata', $request->id_objek_wisata)
            ->where('aktif', '1')
            ->exists();

        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak ditugaskan di objek wisata ini');
        }

        // Check if already clocked in
        $existingShift = $this->getActiveShift();
        if ($existingShift) {
            return back()->with('error', 'Anda masih memiliki shift aktif. Clock out terlebih dahulu.');
        }

        AbsensiCounter::create([
            'id_user' => auth()->id(),
            'id_objek_wisata' => $request->id_objek_wisata,
            'waktu_masuk' => now(),
            'lokasi_masuk' => $request->lokasi ?? null,
        ]);

        return back()->with('success', 'Clock In berhasil! Selamat bertugas.');
    }

    /**
     * Clock out
     */
    public function clockOut(Request $request)
    {
        $activeShift = $this->getActiveShift();

        if (!$activeShift) {
            return back()->with('error', 'Tidak ada shift aktif');
        }

        $activeShift->update([
            'waktu_keluar' => now(),
            'lokasi_keluar' => $request->lokasi ?? null,
            'catatan' => $request->catatan ?? null,
        ]);

        return back()->with('success', 'Clock Out berhasil! Terima kasih atas tugas hari ini.');
    }
}
