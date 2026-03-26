<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Sumbangan;
use App\Models\Danapunia;
use App\Models\Usaha;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function berita()
    {
        $berita = Berita::where('aktif', '1')->orderBy('id_berita', 'desc')->paginate(10);
        $village = ['name' => 'SPDA']; // Fallback
        return view('front.pages.berita.index', compact('berita', 'village'));
    }

    public function berita_detail($id)
    {
        $berita = Berita::where('id_berita', $id)->firstOrFail();
        $recent_berita = Berita::where('aktif', '1')->where('id_berita', '!=', $id)->orderBy('id_berita', 'desc')->take(3)->get();
        $village = ['name' => 'SPDA'];
        return view('front.pages.berita.detail', compact('berita', 'recent_berita', 'village'));
    }

    public function punia()
    {
        $total_punia = Danapunia::where('aktif', '1')->sum('jumlah_dana');
        $village = ['name' => 'SPDA'];
        return view('front.pages.punia', compact('total_punia', 'village'));
    }

    public function donasi()
    {
        $sumbangan = Sumbangan::where('aktif', '1')->orderBy('id_sumbangan_sukarela', 'desc')->take(10)->get();
        $total_sumbangan = Sumbangan::where('aktif', '1')->sum('nominal');
        $village = ['name' => 'SPDA'];
        return view('front.pages.donasi', compact('sumbangan', 'total_sumbangan', 'village'));
    }

    public function donasi_post(Request $request)
    {
        // Simple submission logic, potentially expanding the Sumbangan model's static method
        $id = Sumbangan::submit_post_add_sumbangan($request);
        return redirect()->route('public.donasi')->with('success', 'Terima kasih atas donasi Anda! Bukti pembayaran akan kami verifikasi.');
    }
}
