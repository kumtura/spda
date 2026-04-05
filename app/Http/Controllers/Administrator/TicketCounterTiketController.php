<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\TiketWisata;
use App\Models\TiketDetail;
use App\Models\KategoriTiket;
use App\Models\TicketCounterAssignment;
use App\Models\AbsensiCounter;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketCounterTiketController extends Controller
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
     * Tiket dashboard for ticket counter
     */
    public function index()
    {
        $assignedIds = $this->getAssignedObjekIds();

        $objekWisata = ObjekWisata::with('kategoriTiket')
            ->whereIn('id_objek_wisata', $assignedIds)
            ->where('aktif', '1')
            ->where('status', 'aktif')
            ->get();

        $objekIds = $objekWisata->pluck('id_objek_wisata');

        $tiketHariIni = TiketWisata::whereDate('created_at', today())
            ->where('status_pembayaran', 'completed')
            ->whereIn('id_objek_wisata', $objekIds)
            ->with('details')
            ->get();

        $totalPenjualanHariIni = $tiketHariIni->sum('total_harga');
        $totalTiketTerjual = $tiketHariIni->sum(fn($t) => $t->details->sum('jumlah'));

        return view('backend.ticketcounter.tiket', compact(
            'objekWisata', 'tiketHariIni', 'totalPenjualanHariIni', 'totalTiketTerjual'
        ));
    }

    /**
     * Scan tiket page
     */
    public function scan()
    {
        return view('backend.ticketcounter.tiket_scan');
    }

    /**
     * Jual tiket offline page
     */
    public function jual()
    {
        $assignedIds = $this->getAssignedObjekIds();

        $objekWisata = ObjekWisata::with('kategoriTiket')
            ->whereIn('id_objek_wisata', $assignedIds)
            ->where('aktif', '1')
            ->where('status', 'aktif')
            ->get();

        return view('backend.ticketcounter.tiket_jual', compact('objekWisata'));
    }

    /**
     * Submit offline ticket sale
     */
    public function jualSubmit(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
            'tanggal_kunjungan' => 'required|date',
            'metode_pembayaran' => 'required|in:cash,qris',
            'kategori' => 'required|array|min:1'
        ]);

        // Verify assignment
        $assignedIds = $this->getAssignedObjekIds();
        if (!$assignedIds->contains($request->id_objek_wisata)) {
            return back()->with('error', 'Anda tidak ditugaskan di objek wisata ini');
        }

        // Calculate total
        $total = 0;
        $kategoriData = [];

        foreach ($request->kategori as $kategoriId => $qty) {
            if ($qty > 0) {
                $kategori = KategoriTiket::findOrFail($kategoriId);
                $subtotal = $kategori->harga * $qty;
                $total += $subtotal;

                $kategoriData[] = [
                    'id_kategori_tiket' => $kategoriId,
                    'jumlah' => $qty,
                    'harga_satuan' => $kategori->harga,
                    'subtotal' => $subtotal
                ];
            }
        }

        if ($total == 0) {
            return back()->with('error', 'Pilih minimal 1 tiket');
        }

        $kodeTimestamp = time();
        $kodeRandom = strtoupper(Str::random(6));
        $kodeTicket = "TKT-{$kodeTimestamp}-{$kodeRandom}";

        $tiket = TiketWisata::create([
            'kode_tiket' => $kodeTicket,
            'id_objek_wisata' => $request->id_objek_wisata,
            'email' => null,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'total_harga' => $total,
            'metode_pembelian' => 'offline',
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'completed',
            'status_tiket' => 'sudah_digunakan',
            'waktu_scan' => now(),
            'petugas_scan' => auth()->id(),
            'qr_code' => $kodeTicket,
            'aktif' => '1'
        ]);

        foreach ($kategoriData as $detail) {
            TiketDetail::create([
                'id_tiket' => $tiket->id_tiket,
                'id_kategori_tiket' => $detail['id_kategori_tiket'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        return redirect()->to('administrator/ticketcounter/tiket/jual/success/' . $tiket->id_tiket);
    }

    /**
     * Success page after offline sale
     */
    public function jualSuccess($id)
    {
        $tiket = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])->findOrFail($id);
        return view('backend.ticketcounter.tiket_jual_success', compact('tiket'));
    }

    /**
     * Validate scanned ticket
     */
    public function scanValidate(Request $request)
    {
        $kodeTicket = $request->kode_tiket;

        $tiket = TiketWisata::where('kode_tiket', $kodeTicket)
            ->where('aktif', '1')
            ->with(['objekWisata', 'details.kategoriTiket'])
            ->first();

        if (!$tiket) {
            return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan']);
        }

        if ($tiket->status_pembayaran !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Tiket belum dibayar']);
        }

        if ($tiket->status_tiket === 'sudah_digunakan') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah digunakan',
                'waktu_scan' => $tiket->waktu_scan
            ]);
        }

        if ($tiket->status_tiket === 'expired') {
            return response()->json(['success' => false, 'message' => 'Tiket sudah expired']);
        }

        $tiket->update([
            'status_tiket' => 'sudah_digunakan',
            'waktu_scan' => now(),
            'petugas_scan' => auth()->id()
        ]);

        $kategoriBreakdown = [];
        foreach ($tiket->details as $detail) {
            $kategoriBreakdown[] = [
                'nama' => $detail->kategoriTiket->nama_kategori,
                'jumlah' => $detail->jumlah
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Tiket valid',
            'data' => [
                'kode_tiket' => $tiket->kode_tiket,
                'objek_wisata' => $tiket->objekWisata->nama_objek,
                'kategori' => $kategoriBreakdown,
                'tanggal_kunjungan' => $tiket->tanggal_kunjungan->format('d M Y'),
                'total_harga' => $tiket->total_harga
            ]
        ]);
    }

    /**
     * Transaksi masuk dari payment gateway (online purchases)
     */
    public function transaksi(Request $request)
    {
        $assignedIds = $this->getAssignedObjekIds();

        $query = TiketWisata::whereIn('id_objek_wisata', $assignedIds)
            ->where('aktif', '1')
            ->with(['objekWisata', 'details.kategoriTiket'])
            ->orderBy('created_at', 'desc');

        // Default: hari ini
        $filterDate = $request->get('tanggal', today()->format('Y-m-d'));
        $query->whereDate('created_at', $filterDate);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                  ->orWhere('nama_pengunjung', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $transaksi = $query->get();

        $totalCompleted = $transaksi->where('status_pembayaran', 'completed')->sum('total_harga');
        $totalTransaksi = $transaksi->count();

        return view('backend.ticketcounter.tiket_transaksi', compact(
            'transaksi', 'totalCompleted', 'totalTransaksi', 'filterDate'
        ));
    }

    /**
     * Laporan harian per shift
     */
    public function laporan(Request $request)
    {
        $assignedIds = $this->getAssignedObjekIds();
        $filterDate = $request->get('tanggal', today()->format('Y-m-d'));

        // Absensi on this date
        $absensiHariIni = AbsensiCounter::where('id_user', auth()->id())
            ->whereDate('waktu_masuk', $filterDate)
            ->with('objekWisata')
            ->orderBy('waktu_masuk', 'asc')
            ->get();

        // All tickets sold by this user on this date
        $tiketOffline = TiketWisata::whereDate('created_at', $filterDate)
            ->where('petugas_scan', auth()->id())
            ->where('metode_pembelian', 'offline')
            ->where('status_pembayaran', 'completed')
            ->whereIn('id_objek_wisata', $assignedIds)
            ->with('details')
            ->get();

        // All online tickets scanned by this user on this date
        $tiketOnlineScan = TiketWisata::whereDate('waktu_scan', $filterDate)
            ->where('petugas_scan', auth()->id())
            ->where('metode_pembelian', 'online')
            ->whereIn('id_objek_wisata', $assignedIds)
            ->with('details')
            ->get();

        $totalOffline = $tiketOffline->sum('total_harga');
        $totalTiketOffline = $tiketOffline->sum(fn($t) => $t->details->sum('jumlah'));
        $totalOnlineScan = $tiketOnlineScan->count();

        return view('backend.ticketcounter.tiket_laporan', compact(
            'absensiHariIni', 'tiketOffline', 'tiketOnlineScan',
            'totalOffline', 'totalTiketOffline', 'totalOnlineScan', 'filterDate'
        ));
    }
}
