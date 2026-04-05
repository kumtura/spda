<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ProgramDonasi;
use App\Models\KategoriDonasi;
use App\Models\Sumbangan;
use Illuminate\Support\Facades\DB;

class DonasiApiController extends BaseApiController
{
    /**
     * GET /api/v1/donasi/program
     * List program donasi beserta progres
     */
    public function program(Request $request)
    {
        $query = ProgramDonasi::with('kategori')
            ->where('aktif', '1');

        if ($request->filled('id_kategori_donasi')) {
            $query->where('id_kategori_donasi', $request->id_kategori_donasi);
        }

        $query->orderBy('created_at', 'desc');
        $data = $query->paginate($request->input('per_page', 20));

        // Tambah persentase progres
        $items = collect($data->items())->map(function ($item) {
            $arr = $item->toArray();
            $arr['persentase'] = $item->target_dana > 0
                ? round(($item->terkumpul / $item->target_dana) * 100, 2)
                : 0;
            return $arr;
        });

        return response()->json([
            'success' => true,
            'message' => 'Berhasil',
            'data'    => $items,
            'meta'    => [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
                'last_page'    => $data->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/donasi/program/{id}
     * Detail program donasi + list donatur
     */
    public function programDetail($id)
    {
        $program = ProgramDonasi::with('kategori')
            ->where('aktif', '1')
            ->find($id);

        if (!$program) {
            return $this->errorResponse('Program donasi tidak ditemukan.', 404);
        }

        $donatur = Sumbangan::where('id_program_donasi', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->select('nama', 'nominal', 'status_donatur', 'tanggal', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($item) {
                return [
                    'nama'    => $item->status_donatur == '1' ? 'Anonim' : $item->nama,
                    'nominal' => (float) $item->nominal,
                    'tanggal' => $item->tanggal ?? $item->created_at?->format('Y-m-d'),
                ];
            });

        $data = $program->toArray();
        $data['persentase'] = $program->target_dana > 0
            ? round(($program->terkumpul / $program->target_dana) * 100, 2)
            : 0;
        $data['donatur_terbaru'] = $donatur;

        return $this->successResponse($data);
    }

    /**
     * GET /api/v1/donasi/summary
     * Ringkasan donasi keseluruhan
     */
    public function summary(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $totalDonasi = Sumbangan::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->sum('nominal');

        $totalTransaksi = Sumbangan::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->count();

        $perBulan = Sumbangan::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereYear('created_at', $tahun)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(nominal) as total_nominal')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        $programAktif = ProgramDonasi::where('aktif', '1')->count();
        $programTercapai = ProgramDonasi::where('aktif', '1')
            ->whereColumn('terkumpul', '>=', 'target_dana')
            ->count();

        return $this->successResponse([
            'tahun'            => $tahun,
            'total_donasi'     => (float) $totalDonasi,
            'total_transaksi'  => $totalTransaksi,
            'program_aktif'    => $programAktif,
            'program_tercapai' => $programTercapai,
            'per_bulan'        => $perBulan,
        ]);
    }

    /**
     * GET /api/v1/donasi/kategori
     * List kategori donasi
     */
    public function kategori()
    {
        $data = KategoriDonasi::with(['programs' => function ($q) {
            $q->where('aktif', '1');
        }])
        ->where('aktif', '1')
        ->get();

        return $this->successResponse($data);
    }
}
