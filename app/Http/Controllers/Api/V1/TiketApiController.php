<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\TiketWisata;
use App\Models\ObjekWisata;
use App\Models\KategoriTiket;
use Illuminate\Support\Facades\DB;

class TiketApiController extends BaseApiController
{
    /**
     * GET /api/v1/tiket/objek-wisata
     * List objek wisata dan ketersediaan
     */
    public function objekWisata(Request $request)
    {
        $query = ObjekWisata::with(['kategoriTiket', 'banjar'])
            ->where('aktif', '1');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get()->map(function ($objek) {
            $arr = $objek->toArray();
            // Hitung tiket terjual hari ini
            $terjualHariIni = TiketWisata::where('id_objek_wisata', $objek->id_objek_wisata)
                ->where('tanggal_kunjungan', today())
                ->where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->count();
            $arr['tiket_terjual_hari_ini'] = $terjualHariIni;
            $arr['sisa_kapasitas'] = $objek->batas_tiket_harian
                ? max(0, $objek->batas_tiket_harian - $terjualHariIni)
                : null;
            return $arr;
        });

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/tiket/objek-wisata/{id}
     * Detail objek wisata + kategori tiket + harga
     */
    public function objekWisataDetail($id)
    {
        $objek = ObjekWisata::with(['kategoriTiket', 'banjar'])
            ->where('aktif', '1')
            ->find($id);

        if (!$objek) {
            return $this->errorResponse('Objek wisata tidak ditemukan.', 404);
        }

        $data = $objek->toArray();

        // Tiket terjual hari ini
        $terjualHariIni = TiketWisata::where('id_objek_wisata', $id)
            ->where('tanggal_kunjungan', today())
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->count();

        $data['tiket_terjual_hari_ini'] = $terjualHariIni;
        $data['sisa_kapasitas'] = $objek->batas_tiket_harian
            ? max(0, $objek->batas_tiket_harian - $terjualHariIni)
            : null;

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/tiket/penjualan
     * Data penjualan tiket
     */
    public function penjualan(Request $request)
    {
        $query = TiketWisata::with(['objekWisata', 'details.kategoriTiket'])
            ->where('aktif', '1');

        if ($request->filled('id_objek_wisata')) {
            $query->where('id_objek_wisata', $request->id_objek_wisata);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_kunjungan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_kunjungan', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('status_tiket')) {
            $query->where('status_tiket', $request->status_tiket);
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }

    /**
     * GET /api/v1/tiket/summary
     * Ringkasan penjualan tiket
     */
    public function summary(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $totalPendapatan = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->sum('total_harga');

        $totalTiket = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->count();

        $hariIni = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->where('tanggal_kunjungan', today())
            ->count();

        $pendapatanHariIni = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->where('tanggal_kunjungan', today())
            ->sum('total_harga');

        $perBulan = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah_tiket'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        $perObjek = TiketWisata::where('tb_tiket_wisata.status_pembayaran', 'completed')
            ->where('tb_tiket_wisata.aktif', '1')
            ->whereYear('tb_tiket_wisata.created_at', $tahun)
            ->join('tb_objek_wisata', 'tb_tiket_wisata.id_objek_wisata', '=', 'tb_objek_wisata.id_objek_wisata')
            ->select(
                'tb_objek_wisata.nama_objek',
                DB::raw('COUNT(*) as jumlah_tiket'),
                DB::raw('SUM(tb_tiket_wisata.total_harga) as total_pendapatan')
            )
            ->groupBy('tb_objek_wisata.nama_objek')
            ->get();

        return $this->successResponse([
            'tahun'               => $tahun,
            'total_pendapatan'    => (float) $totalPendapatan,
            'total_tiket'         => $totalTiket,
            'hari_ini'            => [
                'jumlah_tiket'    => $hariIni,
                'pendapatan'      => (float) $pendapatanHariIni,
            ],
            'per_bulan'           => $perBulan,
            'per_objek_wisata'    => $perObjek,
        ]);
    }

    /**
     * GET /api/v1/tiket/ketersediaan/{id}
     * Cek ketersediaan tiket untuk tanggal tertentu
     */
    public function ketersediaan($id, Request $request)
    {
        $objek = ObjekWisata::where('aktif', '1')->find($id);

        if (!$objek) {
            return $this->errorResponse('Objek wisata tidak ditemukan.', 404);
        }

        $tanggal = $request->input('tanggal', today()->format('Y-m-d'));

        $terjual = TiketWisata::where('id_objek_wisata', $id)
            ->where('tanggal_kunjungan', $tanggal)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->count();

        return $this->successResponse([
            'id_objek_wisata'   => $objek->id_objek_wisata,
            'nama_objek'        => $objek->nama_objek,
            'tanggal'           => $tanggal,
            'kapasitas_harian'  => $objek->batas_tiket_harian,
            'tiket_terjual'     => $terjual,
            'sisa'              => $objek->batas_tiket_harian
                ? max(0, $objek->batas_tiket_harian - $terjual)
                : null,
            'tersedia'          => $objek->batas_tiket_harian
                ? ($terjual < $objek->batas_tiket_harian)
                : true,
        ]);
    }
}
