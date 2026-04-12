<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Pura;
use App\Models\GalleryPura;
use App\Models\PuniaPura;
use App\Models\QrisPura;
use App\Models\Banjar;
use App\Services\BpdBaliQrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuraController extends Controller
{
    // ==================== PURA CRUD ====================

    public function index()
    {
        $pura = Pura::where('tb_pura.aktif', '1')
            ->leftJoin('tb_data_banjar', 'tb_pura.id_data_banjar', '=', 'tb_data_banjar.id_data_banjar')
            ->select('tb_pura.*', 'tb_data_banjar.nama_banjar')
            ->orderBy('tb_pura.id_pura', 'desc')
            ->get();

        $banjar = Banjar::where('aktif', '1')->get();

        return view('admin.pages.pura.table', compact('pura', 'banjar'));
    }

    public function create()
    {
        $banjar = Banjar::where('aktif', '1')->get();
        return view('admin.pages.pura.form', compact('banjar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pura' => 'required|string|max:200',
            'id_data_banjar' => 'nullable|exists:tb_data_banjar,id_data_banjar',
            'gambar_pura' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'nama_pura', 'lokasi', 'latitude', 'longitude',
            'nama_ketua_pura', 'no_telp_ketua', 'id_data_banjar',
            'nama_pemangku', 'wuku_odalan', 'odalan_terdekat', 'deskripsi'
        ]);

        // Upload gambar utama
        if ($request->hasFile('gambar_pura')) {
            $file = $request->file('gambar_pura');
            $filename = 'pura_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage/pura');
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $data['gambar_pura'] = 'storage/pura/' . $filename;
        }

        $pura = Pura::create($data);

        // Upload gallery
        if ($request->hasFile('gallery')) {
            $urutan = 0;
            $galleryDir = public_path('storage/pura/gallery');
            if (!file_exists($galleryDir)) mkdir($galleryDir, 0755, true);

            foreach ($request->file('gallery') as $file) {
                $gFilename = 'gallery_' . $pura->id_pura . '_' . time() . '_' . $urutan . '.' . $file->getClientOriginalExtension();
                $file->move($galleryDir, $gFilename);
                GalleryPura::create([
                    'id_pura' => $pura->id_pura,
                    'gambar' => 'storage/pura/gallery/' . $gFilename,
                    'urutan' => $urutan++
                ]);
            }
        }

        return redirect('administrator/puniapura')->with('success', 'Data pura berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pura = Pura::findOrFail($id);
        $banjar = Banjar::where('aktif', '1')->get();
        $gallery = GalleryPura::where('id_pura', $id)->where('aktif', '1')->orderBy('urutan')->get();
        return view('admin.pages.pura.form', compact('pura', 'banjar', 'gallery'));
    }

    public function update(Request $request, $id)
    {
        $pura = Pura::findOrFail($id);

        $request->validate([
            'nama_pura' => 'required|string|max:200',
            'id_data_banjar' => 'nullable|exists:tb_data_banjar,id_data_banjar',
            'gambar_pura' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'nama_pura', 'lokasi', 'latitude', 'longitude',
            'nama_ketua_pura', 'no_telp_ketua', 'id_data_banjar',
            'nama_pemangku', 'wuku_odalan', 'odalan_terdekat', 'deskripsi'
        ]);

        if ($request->hasFile('gambar_pura')) {
            $file = $request->file('gambar_pura');
            $filename = 'pura_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('storage/pura');
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $data['gambar_pura'] = 'storage/pura/' . $filename;
        }

        $pura->update($data);

        // Upload new gallery items
        if ($request->hasFile('gallery')) {
            $maxUrutan = GalleryPura::where('id_pura', $id)->max('urutan') ?? 0;
            $galleryDir = public_path('storage/pura/gallery');
            if (!file_exists($galleryDir)) mkdir($galleryDir, 0755, true);

            foreach ($request->file('gallery') as $file) {
                $maxUrutan++;
                $gFilename = 'gallery_' . $id . '_' . time() . '_' . $maxUrutan . '.' . $file->getClientOriginalExtension();
                $file->move($galleryDir, $gFilename);
                GalleryPura::create([
                    'id_pura' => $id,
                    'gambar' => 'storage/pura/gallery/' . $gFilename,
                    'urutan' => $maxUrutan
                ]);
            }
        }

        return redirect('administrator/puniapura')->with('success', 'Data pura berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pura = Pura::findOrFail($id);
        $pura->update(['aktif' => '0']);
        return redirect('administrator/puniapura')->with('success', 'Data pura berhasil dihapus');
    }

    public function deleteGallery($id)
    {
        $gallery = GalleryPura::findOrFail($id);
        $gallery->update(['aktif' => '0']);
        return redirect()->back()->with('success', 'Foto gallery berhasil dihapus');
    }

    // ==================== DETAIL & LOG PUNIA ====================

    public function detail($id)
    {
        $pura = Pura::leftJoin('tb_data_banjar', 'tb_pura.id_data_banjar', '=', 'tb_data_banjar.id_data_banjar')
            ->select('tb_pura.*', 'tb_data_banjar.nama_banjar')
            ->where('tb_pura.id_pura', $id)
            ->firstOrFail();

        $gallery = GalleryPura::where('id_pura', $id)->where('aktif', '1')->orderBy('urutan')->get();

        $punia = PuniaPura::where('id_pura', $id)
            ->where('aktif', '1')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalPunia = PuniaPura::where('id_pura', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->sum('nominal');

        $totalOnline = PuniaPura::where('id_pura', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereIn('metode_pembayaran', ['xendit', 'qris_bpd'])
            ->sum('nominal');

        $totalManual = PuniaPura::where('id_pura', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->where('metode_pembayaran', 'manual')
            ->sum('nominal');

        $puniaHariIni = PuniaPura::where('id_pura', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereDate('tanggal_pembayaran', today())
            ->sum('nominal');

        $qris = QrisPura::where('id_pura', $id)->where('is_active', '1')->first();

        return view('admin.pages.pura.detail', compact(
            'pura', 'gallery', 'punia', 'totalPunia', 'totalOnline',
            'totalManual', 'puniaHariIni', 'qris'
        ));
    }

    // ==================== QRIS BPD BALI ====================

    public function qrisForm($id)
    {
        $pura = Pura::findOrFail($id);
        $qris = QrisPura::where('id_pura', $id)->where('is_active', '1')->first();
        return view('admin.pages.pura.qris_form', compact('pura', 'qris'));
    }

    public function qrisSave(Request $request, $id)
    {
        $pura = Pura::findOrFail($id);

        $request->validate([
            'qris_content' => 'required|string|max:500',
            'nmid' => 'nullable|string|max:100',
            'merchant_name' => 'nullable|string|max:200',
        ]);

        // Deactivate existing QRIS
        QrisPura::where('id_pura', $id)->update(['is_active' => '0']);

        // Generate QR image
        $service = new BpdBaliQrisService();
        $filename = 'qris_pura_' . $id . '_' . time() . '.png';
        $imagePath = $service->generateQrImage($request->qris_content, $filename);

        QrisPura::create([
            'id_pura' => $id,
            'qris_content' => $request->qris_content,
            'qris_image' => $imagePath,
            'nmid' => $request->nmid,
            'merchant_name' => $request->merchant_name ?? $pura->nama_pura,
            'is_active' => '1'
        ]);

        return redirect('administrator/puniapura/detail/' . $id)->with('success', 'QRIS berhasil disimpan');
    }

    public function qrisDownload($id)
    {
        $qris = QrisPura::where('id_pura', $id)->where('is_active', '1')->firstOrFail();

        if ($qris->qris_image && file_exists(public_path($qris->qris_image))) {
            return response()->download(public_path($qris->qris_image), 'QRIS_' . $qris->merchant_name . '.png');
        }

        return redirect()->back()->with('error', 'File QRIS tidak ditemukan');
    }

    // ==================== TARIK PUNIA (WITHDRAW) ====================

    public function tarikPunia(Request $request, $id)
    {
        $pura = Pura::findOrFail($id);

        $request->validate([
            'nominal' => 'required|numeric|min:1000',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Record withdrawal as negative log entry
        PuniaPura::create([
            'id_pura' => $id,
            'nama_donatur' => 'PENARIKAN',
            'nominal' => -abs($request->nominal),
            'metode_pembayaran' => 'tarik',
            'status_pembayaran' => 'completed',
            'tanggal_pembayaran' => now()->toDateString(),
            'keterangan' => $request->keterangan ?? 'Penarikan dana punia pura',
            'aktif' => '1'
        ]);

        return redirect('administrator/puniapura/detail/' . $id)->with('success', 'Dana punia berhasil ditarik');
    }

    // ==================== GENERATE QRIS XENDIT (DYNAMIC) ====================

    public function generateQrisXendit($id)
    {
        $pura = Pura::findOrFail($id);

        // This generates a dynamic Xendit QRIS for one-time display
        $xendit = new \App\Services\XenditService();
        if (!$xendit->isConfigured()) {
            return redirect()->back()->with('error', 'Xendit belum dikonfigurasi');
        }

        $external_id = 'PP-' . $id . '-' . time();
        $response = $xendit->createQRCode($external_id, 0); // 0 = open amount

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->with('error', 'Gagal generate QRIS: ' . ($response['message'] ?? 'Unknown error'));
        }

        return redirect()->back()->with([
            'success' => 'QRIS Xendit berhasil digenerate',
            'xendit_qris' => $response
        ]);
    }
}
