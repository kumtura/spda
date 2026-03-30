<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\TiketWisata;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Session;

class TiketWisataController extends Controller
{
    public function index()
    {
        return view('backend.kelian.tiket');
    }

    public function scan()
    {
        return view('backend.kelian.tiket_scan');
    }

    public function jual()
    {
        $objekWisata = ObjekWisata::where('aktif', '1')->where('status', 'aktif')->get();
        return view('backend.kelian.tiket_jual', compact('objekWisata'));
    }

    public function jual_submit(Request $request)
    {
        $request->validate([
            'id_objek_wisata' => 'required',
            'nama_pengunjung' => 'required',
            'jumlah_tiket' => 'required|integer|min:1',
            'tanggal_kunjungan' => 'required|date'
        ]);

        $objek = ObjekWisata::find($request->id_objek_wisata);
        $kodeTimestamp = time();
        $kodeRandom = strtoupper(Str::random(6));
        $kodeTicket = "TKT-{$kodeTimestamp}-{$kodeRandom}";

        $tiket = TiketWisata::create([
            'kode_tiket' => $kodeTicket,
            'id_objek_wisata' => $request->id_objek_wisata,
            'nama_pengunjung' => $request->nama_pengunjung,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jumlah_tiket' => $request->jumlah_tiket,
            'total_harga' => $objek->harga_tiket * $request->jumlah_tiket,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'metode_pembelian' => 'offline',
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'completed',
            'status_tiket' => 'belum_digunakan',
            'qr_code' => $kodeTicket,
            'aktif' => '1'
        ]);

        return redirect()->to('administrator/tiket/jual/success/' . $tiket->id_tiket);
    }

    public function jual_success($id)
    {
        $tiket = TiketWisata::with('objekWisata')->findOrFail($id);
        return view('backend.kelian.tiket_jual_success', compact('tiket'));
    }

    public function scan_validate(Request $request)
    {
        $kodeTicket = $request->kode_tiket;
        
        $tiket = TiketWisata::where('kode_tiket', $kodeTicket)
            ->where('aktif', '1')
            ->with('objekWisata')
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

        return response()->json([
            'success' => true,
            'message' => 'Tiket valid',
            'data' => [
                'kode_tiket' => $tiket->kode_tiket,
                'nama_pengunjung' => $tiket->nama_pengunjung,
                'objek_wisata' => $tiket->objekWisata->nama_objek,
                'jumlah_tiket' => $tiket->jumlah_tiket,
                'tanggal_kunjungan' => $tiket->tanggal_kunjungan->format('d M Y')
            ]
        ]);
    }
}

