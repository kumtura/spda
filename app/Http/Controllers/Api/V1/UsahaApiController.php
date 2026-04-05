<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Usaha;
use App\Models\Detail_Usaha;
use App\Models\Kategori_Usaha;
use App\Models\Danapunia;
use Illuminate\Support\Facades\DB;

class UsahaApiController extends BaseApiController
{
    /**
     * GET /api/v1/usaha
     * List unit usaha
     */
    public function index(Request $request)
    {
        $query = Usaha::with(['detail', 'kategori'])
            ->where('aktif', '1');

        if ($request->filled('id_jenis_usaha')) {
            $query->where('id_jenis_usaha', $request->id_jenis_usaha);
        }
        if ($request->filled('aktif_status')) {
            $query->where('aktif_status', $request->aktif_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('detail', function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate($request->input('per_page', 20));

        return $this->paginatedResponse($data);
    }

    /**
     * GET /api/v1/usaha/{id}
     * Detail usaha + statistik punia
     */
    public function show($id)
    {
        $usaha = Usaha::with(['detail', 'kategori'])
            ->where('aktif', '1')
            ->find($id);

        if (!$usaha) {
            return $this->errorResponse('Unit usaha tidak ditemukan.', 404);
        }

        // Statistik punia usaha ini
        $totalPunia = Danapunia::where('id_usaha', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->sum('jumlah_dana');

        $puniaTerakhir = Danapunia::where('id_usaha', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->orderBy('created_at', 'desc')
            ->first();

        $data = $usaha->toArray();
        $data['statistik_punia'] = [
            'total_dibayar'   => (float) $totalPunia,
            'terakhir_bayar'  => $puniaTerakhir?->created_at,
            'bulan_terakhir'  => $puniaTerakhir ? $puniaTerakhir->bulan_punia . '/' . $puniaTerakhir->tahun_punia : null,
        ];

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/usaha/kategori
     * List kategori usaha
     */
    public function kategori()
    {
        $data = Kategori_Usaha::where('aktif', '1')->get();

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/usaha/belum-punia
     * Usaha yang belum bayar punia bulan ini
     */
    public function belumPunia(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $sudahBayar = Danapunia::where('bulan_punia', $bulan)
            ->where('tahun_punia', $tahun)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->pluck('id_usaha');

        $belumBayar = Usaha::with(['detail', 'kategori'])
            ->where('aktif', '1')
            ->where('aktif_status', '1')
            ->whereNotIn('id_usaha', $sudahBayar)
            ->get();

        return $this->successResponse([
            'bulan'       => (int) $bulan,
            'tahun'       => (int) $tahun,
            'total_belum' => $belumBayar->count(),
            'data'        => $belumBayar,
        ]);
    }
}
