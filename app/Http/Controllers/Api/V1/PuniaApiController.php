<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Danapunia;
use App\Models\KategoriPunia;
use App\Models\AlokasiPunia;
use App\Models\PuniaPendatang;
use Illuminate\Support\Facades\DB;

class PuniaApiController extends BaseApiController
{
    /**
     * GET /api/v1/punia
     * List data punia usaha dengan filter
     */
    public function index(Request $request)
    {
        $query = Danapunia::with('usaha.detail')
            ->where('aktif', '1');

        if ($request->filled('bulan')) {
            $query->where('bulan_punia', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun_punia', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }
        if ($request->filled('id_usaha')) {
            $query->where('id_usaha', $request->id_usaha);
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }

    /**
     * GET /api/v1/punia/summary
     * Ringkasan total punia per bulan/tahun
     */
    public function summary(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $monthly = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('tahun_punia', $tahun)
            ->select(
                'bulan_punia',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(jumlah_dana) as total_dana')
            )
            ->groupBy('bulan_punia')
            ->orderBy('bulan_punia')
            ->get();

        $totalTahun = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('tahun_punia', $tahun)
            ->sum('jumlah_dana');

        $totalTransaksi = Danapunia::where('aktif', '1')
            ->where('status_pembayaran', 'completed')
            ->where('tahun_punia', $tahun)
            ->count();

        return $this->successResponse([
            'tahun'            => $tahun,
            'total_dana'       => (float) $totalTahun,
            'total_transaksi'  => $totalTransaksi,
            'per_bulan'        => $monthly,
        ]);
    }

    /**
     * GET /api/v1/punia/kategori
     * List kategori punia beserta alokasi
     */
    public function kategori()
    {
        $data = KategoriPunia::with('alokasi')
            ->where('aktif', '1')
            ->get();

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/punia/alokasi
     * Data penggunaan/alokasi dana punia
     */
    public function alokasi(Request $request)
    {
        $query = AlokasiPunia::with('kategori')
            ->where('aktif', '1');

        if ($request->filled('id_kategori_punia')) {
            $query->where('id_kategori_punia', $request->id_kategori_punia);
        }

        $query->orderBy('tanggal_alokasi', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }

    /**
     * GET /api/v1/punia/pendatang
     * Data punia dari krama tamiu/pendatang
     */
    public function puniaPendatang(Request $request)
    {
        $query = PuniaPendatang::with(['pendatang', 'acaraPunia'])
            ->where('aktif', 1);

        if ($request->filled('jenis_punia')) {
            $query->where('jenis_punia', $request->jenis_punia);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('bulan_tahun')) {
            $query->where('bulan_tahun', $request->bulan_tahun);
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }
}
