<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Pura;
use App\Models\GalleryPura;
use App\Models\PuniaPura;
use App\Models\QrisPura;
use App\Models\Banjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPuraController extends Controller
{
    protected function getMyPura()
    {
        $user = Auth::user();
        if (!$user || !$user->id_pura) {
            return null;
        }
        return Pura::with('gallery')->find($user->id_pura);
    }

    public function home()
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return view('backend.adminpura.home', ['pura' => null]);
        }

        $gallery = GalleryPura::where('id_pura', $pura->id_pura)
            ->where('aktif', '1')
            ->orderBy('urutan')
            ->get();

        $totalPunia = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->sum('nominal');

        $puniaHariIni = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereDate('tanggal_pembayaran', today())
            ->sum('nominal');

        $transaksiHariIni = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereDate('tanggal_pembayaran', today())
            ->count();

        $qris = QrisPura::where('id_pura', $pura->id_pura)
            ->where('is_active', '1')
            ->first();

        $banjar = Banjar::where('aktif', '1')->get();

        return view('backend.adminpura.home', compact(
            'pura', 'gallery', 'totalPunia', 'puniaHariIni',
            'transaksiHariIni', 'qris', 'banjar'
        ));
    }

    public function updatePura(Request $request)
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $request->validate([
            'nama_pura' => 'required|string|max:200',
            'gambar_pura' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'nama_pura', 'lokasi', 'google_maps_url',
            'nama_pemangku', 'no_telp_pemangku',
            'wuku_odalan', 'odalan_terdekat', 'deskripsi'
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
            $maxUrutan = GalleryPura::where('id_pura', $pura->id_pura)->max('urutan') ?? 0;
            $galleryDir = public_path('storage/pura/gallery');
            if (!file_exists($galleryDir)) mkdir($galleryDir, 0755, true);

            foreach ($request->file('gallery') as $file) {
                $maxUrutan++;
                $gFilename = 'gallery_' . $pura->id_pura . '_' . time() . '_' . $maxUrutan . '.' . $file->getClientOriginalExtension();
                $file->move($galleryDir, $gFilename);
                GalleryPura::create([
                    'id_pura' => $pura->id_pura,
                    'gambar' => 'storage/pura/gallery/' . $gFilename,
                    'urutan' => $maxUrutan
                ]);
            }
        }

        return redirect('administrator/pura/home')->with('success', 'Data pura berhasil diperbarui.');
    }

    public function deleteGallery($id)
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $gallery = GalleryPura::where('id_gallery_pura', $id)
            ->where('id_pura', $pura->id_pura)
            ->firstOrFail();

        $gallery->update(['aktif' => '0']);

        return redirect('administrator/pura/home')->with('success', 'Foto gallery berhasil dihapus.');
    }

    public function punia()
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $punia = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('aktif', '1')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalPunia = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->sum('nominal');

        return view('backend.adminpura.punia', compact('pura', 'punia', 'totalPunia'));
    }

    public function verifikasi()
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $pending = PuniaPura::where('id_pura', $pura->id_pura)
            ->where('aktif', '1')
            ->where('metode_pembayaran', 'manual')
            ->where('status_verifikasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.adminpura.verifikasi', compact('pura', 'pending'));
    }

    public function approve(Request $request)
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $request->validate([
            'id_punia_pura' => 'required|integer',
        ]);

        $punia = PuniaPura::where('id_punia_pura', $request->id_punia_pura)
            ->where('id_pura', $pura->id_pura)
            ->where('aktif', '1')
            ->firstOrFail();

        $punia->update([
            'status_verifikasi' => 'approved',
            'status_pembayaran' => 'completed',
            'tanggal_pembayaran' => $punia->tanggal_pembayaran ?: now()->toDateString(),
        ]);

        return redirect('administrator/pura/verifikasi')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request)
    {
        $pura = $this->getMyPura();
        if (!$pura) {
            return redirect('administrator/pura/home')->with('error', 'Pura tidak ditemukan.');
        }

        $request->validate([
            'id_punia_pura' => 'required|integer',
            'catatan_verifikasi' => 'required|string|min:5|max:500',
        ]);

        $punia = PuniaPura::where('id_punia_pura', $request->id_punia_pura)
            ->where('id_pura', $pura->id_pura)
            ->where('aktif', '1')
            ->firstOrFail();

        $punia->update([
            'status_verifikasi' => 'rejected',
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        return redirect('administrator/pura/verifikasi')->with('success', 'Pembayaran ditolak.');
    }
}
