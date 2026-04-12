<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\PengaturanBagiHasil;
use App\Models\Banjar;

class PengaturanBagiHasilController extends BaseController
{
    public function index()
    {
        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();

        // Get current global settings
        $globalTamiu = PengaturanBagiHasil::where('jenis_punia', 'tamiu')
            ->whereNull('id_data_banjar')
            ->where('aktif', 1)
            ->orderBy('berlaku_sejak', 'desc')
            ->first();

        $globalUsaha = PengaturanBagiHasil::where('jenis_punia', 'usaha')
            ->whereNull('id_data_banjar')
            ->where('aktif', 1)
            ->orderBy('berlaku_sejak', 'desc')
            ->first();

        // Get banjar-specific overrides
        $overrides = PengaturanBagiHasil::with('banjar')
            ->whereNotNull('id_data_banjar')
            ->where('aktif', 1)
            ->orderBy('jenis_punia')
            ->orderBy('berlaku_sejak', 'desc')
            ->get()
            ->groupBy('id_data_banjar');

        // Build per-banjar summary for the table
        $banjarSettings = [];
        foreach ($banjarList as $b) {
            $overrideTamiu = PengaturanBagiHasil::where('jenis_punia', 'tamiu')
                ->where('id_data_banjar', $b->id_data_banjar)
                ->where('aktif', 1)
                ->orderBy('berlaku_sejak', 'desc')
                ->first();

            $overrideUsaha = PengaturanBagiHasil::where('jenis_punia', 'usaha')
                ->where('id_data_banjar', $b->id_data_banjar)
                ->where('aktif', 1)
                ->orderBy('berlaku_sejak', 'desc')
                ->first();

            $banjarSettings[] = [
                'banjar' => $b,
                'tamiu' => $overrideTamiu,
                'usaha' => $overrideUsaha,
                'tamiu_effective' => $overrideTamiu ?: $globalTamiu,
                'usaha_effective' => $overrideUsaha ?: $globalUsaha,
                'has_override' => $overrideTamiu || $overrideUsaha,
            ];
        }

        // History log
        $riwayat = PengaturanBagiHasil::with('banjar')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.pages.pengaturan_bagi_hasil.index', compact(
            'banjarList', 'globalTamiu', 'globalUsaha',
            'banjarSettings', 'riwayat'
        ));
    }

    /**
     * Save global settings for both tamiu and usaha.
     */
    public function storeGlobal(Request $request)
    {
        $request->validate([
            'persen_desa_tamiu' => 'required|numeric|min:0|max:100',
            'persen_desa_usaha' => 'required|numeric|min:0|max:100',
            'berlaku_sejak' => 'required|date',
        ]);

        // Deactivate old global tamiu
        PengaturanBagiHasil::where('jenis_punia', 'tamiu')
            ->whereNull('id_data_banjar')
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        // Deactivate old global usaha
        PengaturanBagiHasil::where('jenis_punia', 'usaha')
            ->whereNull('id_data_banjar')
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        // Create new global tamiu
        PengaturanBagiHasil::create([
            'jenis_punia' => 'tamiu',
            'id_data_banjar' => null,
            'persen_desa' => $request->persen_desa_tamiu,
            'persen_banjar' => 100 - $request->persen_desa_tamiu,
            'berlaku_sejak' => $request->berlaku_sejak,
            'keterangan' => 'Pengaturan global punia Krama Tamiu',
        ]);

        // Create new global usaha
        PengaturanBagiHasil::create([
            'jenis_punia' => 'usaha',
            'id_data_banjar' => null,
            'persen_desa' => $request->persen_desa_usaha,
            'persen_banjar' => 100 - $request->persen_desa_usaha,
            'berlaku_sejak' => $request->berlaku_sejak,
            'keterangan' => 'Pengaturan global punia Unit Usaha',
        ]);

        return redirect('administrator/pengaturan_bagi_hasil')->with('success', 'Pengaturan global berhasil disimpan.');
    }

    /**
     * Save override setting for a specific banjar.
     */
    public function storeBanjar(Request $request)
    {
        $request->validate([
            'id_data_banjar' => 'required|exists:tb_data_banjar,id_data_banjar',
            'jenis_punia' => 'required|in:tamiu,usaha',
            'persen_desa' => 'required|numeric|min:0|max:100',
            'berlaku_sejak' => 'required|date',
        ]);

        // Deactivate old override for this banjar + jenis
        PengaturanBagiHasil::where('jenis_punia', $request->jenis_punia)
            ->where('id_data_banjar', $request->id_data_banjar)
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        PengaturanBagiHasil::create([
            'jenis_punia' => $request->jenis_punia,
            'id_data_banjar' => $request->id_data_banjar,
            'persen_desa' => $request->persen_desa,
            'persen_banjar' => 100 - $request->persen_desa,
            'berlaku_sejak' => $request->berlaku_sejak,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('administrator/pengaturan_bagi_hasil')->with('success', 'Pengaturan khusus banjar berhasil disimpan.');
    }

    /**
     * Apply global settings to ALL banjars (quick setup).
     * This removes all banjar-specific overrides.
     */
    public function terapkanSemua(Request $request)
    {
        // Deactivate all banjar-specific overrides
        PengaturanBagiHasil::whereNotNull('id_data_banjar')
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        return redirect('administrator/pengaturan_bagi_hasil')->with('success', 'Semua pengaturan khusus banjar telah dihapus. Sekarang semua banjar menggunakan pengaturan global.');
    }

    /**
     * Remove a banjar-specific override.
     */
    public function hapusOverride($id)
    {
        $setting = PengaturanBagiHasil::findOrFail($id);
        $setting->update(['aktif' => 0]);

        return redirect('administrator/pengaturan_bagi_hasil')->with('success', 'Override banjar berhasil dihapus. Banjar akan menggunakan pengaturan global.');
    }
}
