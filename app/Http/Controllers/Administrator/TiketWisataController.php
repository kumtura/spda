<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\TiketWisata;
use App\Models\TiketDetail;
use App\Models\KategoriTiket;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Session;

class TiketWisataController extends Controller
{
    public function index()
    {
        $banjar = auth()->user()->banjar;
        $idBanjar = $banjar ? $banjar->id_data_banjar : 0;

        $objekWisata = ObjekWisata::with('kategoriTiket')
            ->where('id_data_banjar', $idBanjar)
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

        return view('backend.kelian.tiket', compact(
            'objekWisata', 'tiketHariIni', 'totalPenjualanHariIni', 'totalTiketTerjual'
        ));
    }

    public function scan()
    {
        return view('backend.kelian.tiket_scan');
    }

    public function jual()
    {
        $query = ObjekWisata::with('kategoriTiket')
            ->where('aktif', '1')
            ->where('status', 'aktif');
            
        if (auth()->user()->id_level != config('myconfig.level.bendesa', 1)) {
            $banjar = auth()->user()->banjar;
            $idBanjar = $banjar ? $banjar->id_data_banjar : 0;
            $query->where('id_data_banjar', $idBanjar);
        }
            
        $objekWisata = $query->get();
        return view('backend.kelian.tiket_jual', compact('objekWisata'));
    }

    public function jual_submit(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required|exists:tb_objek_wisata,id_objek_wisata',
            'tanggal_kunjungan' => 'required|date',
            'metode_pembayaran' => 'required|in:cash,qris',
            'kategori' => 'required|array|min:1'
        ]);

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

        // Generate kode tiket
        $kodeTimestamp = time();
        $kodeRandom = strtoupper(Str::random(6));
        $kodeTicket = "TKT-{$kodeTimestamp}-{$kodeRandom}";

        // Create tiket
        $tiket = TiketWisata::create([
            'kode_tiket' => $kodeTicket,
            'id_objek_wisata' => $request->id_objek_wisata,
            'email' => null,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'total_harga' => $total,
            'metode_pembelian' => 'offline',
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'completed',
            'status_tiket' => 'sudah_digunakan', // Offline langsung masuk
            'waktu_scan' => now(),
            'petugas_scan' => auth()->id(),
            'qr_code' => $kodeTicket,
            'aktif' => '1'
        ]);

        // Create tiket details
        foreach ($kategoriData as $detail) {
            TiketDetail::create([
                'id_tiket' => $tiket->id_tiket,
                'id_kategori_tiket' => $detail['id_kategori_tiket'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        return redirect()->to('administrator/kelian/tiket/jual/success/' . $tiket->id_tiket);
    }

    public function jual_success($id)
    {
        $tiket = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])->findOrFail($id);
        return view('backend.kelian.tiket_jual_success', compact('tiket'));
    }

    public function scan_validate(Request $request)
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

        // Update tiket status
        $tiket->update([
            'status_tiket' => 'sudah_digunakan',
            'waktu_scan' => now(),
            'petugas_scan' => auth()->id()
        ]);

        // Build category breakdown
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
}

