<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Pendatang;
use App\Models\PuniaPendatang;
use App\Models\AcaraPunia;
use App\Models\Banjar;
use Illuminate\Support\Facades\DB;

class KramaTamiuApiController extends BaseApiController
{
    /**
     * GET /api/v1/krama-tamiu
     * List pendatang/krama tamiu
     */
    public function index(Request $request)
    {
        $query = Pendatang::with('banjar');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('id_data_banjar')) {
            $query->where('id_data_banjar', $request->id_data_banjar);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('asal', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }

    /**
     * GET /api/v1/krama-tamiu/count
     * Statistik jumlah krama tamiu
     */
    public function count(Request $request)
    {
        $total = Pendatang::count();
        $aktif = Pendatang::where('status', 'aktif')->count();
        $nonaktif = Pendatang::where('status', 'nonaktif')->count();

        $perBanjar = Pendatang::where('status', 'aktif')
            ->join('tb_data_banjar', 'tb_pendatang.id_data_banjar', '=', 'tb_data_banjar.id_data_banjar')
            ->select('tb_data_banjar.nama_banjar', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('tb_data_banjar.nama_banjar')
            ->get();

        return $this->successResponse([
            'total'      => $total,
            'aktif'      => $aktif,
            'nonaktif'   => $nonaktif,
            'per_banjar' => $perBanjar,
        ]);
    }

    /**
     * GET /api/v1/krama-tamiu/{id}
     * Detail pendatang + histori punia
     */
    public function show($id)
    {
        $pendatang = Pendatang::with(['banjar', 'puniaPendatang.acaraPunia'])
            ->find($id);

        if (!$pendatang) {
            return $this->errorResponse('Pendatang tidak ditemukan.', 404);
        }

        $totalPunia = $pendatang->puniaPendatang()
            ->where('status_pembayaran', 'lunas')
            ->where('aktif', 1)
            ->sum('nominal');

        $data = $pendatang->toArray();
        $data['total_punia_dibayar'] = (float) $totalPunia;

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/krama-tamiu/belum-punia
     * Pendatang yang belum bayar punia bulan ini
     */
    public function belumPunia(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun', date('Y-m'));

        // Pendatang aktif yang belum punya record punia rutin bulan ini
        $sudahBayar = PuniaPendatang::where('jenis_punia', 'rutin')
            ->where('bulan_tahun', $bulanTahun)
            ->where('aktif', 1)
            ->pluck('id_pendatang');

        $belumBayar = Pendatang::with('banjar')
            ->where('status', 'aktif')
            ->where('punia_rutin_bulanan', '>', 0)
            ->whereNotIn('id_pendatang', $sudahBayar)
            ->get();

        return $this->successResponse([
            'bulan_tahun'   => $bulanTahun,
            'total_belum'   => $belumBayar->count(),
            'data'          => $belumBayar,
        ]);
    }

    /**
     * GET /api/v1/krama-tamiu/acara-punia
     * List acara punia dan statusnya
     */
    public function acaraPunia(Request $request)
    {
        $query = AcaraPunia::withCount([
            'puniaPendatang as total_terbayar' => function ($q) {
                $q->where('status_pembayaran', 'lunas')->where('aktif', 1);
            },
            'puniaPendatang as total_belum' => function ($q) {
                $q->where('status_pembayaran', 'belum_bayar')->where('aktif', 1);
            },
        ])->where('aktif', 1);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('tanggal_acara', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }
}
