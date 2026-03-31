<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use File;

class SettingController extends Controller
{
    public function index()
    {
        $settingsPath = storage_path('app/settings.json');
        $village = [];
        if (File::exists($settingsPath)) {
            $village = json_decode(File::get($settingsPath), true);
        }
        return view('admin.pages.settings.index', compact('village'));
    }

    public function update_village(Request $request)
    {
        $data = [
            'name' => $request->village_name,
            'bendesa' => $request->bendesa_name,
            'address' => $request->village_address,
        ];

        File::put(storage_path('app/settings.json'), json_encode($data));

        return redirect()->back()->with('success', 'Identitas Desa berhasil diperbarui!');
    }

    public function update_logo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo.png'; // Overwrite standard logo
            
            // Simpan ke storage/app/public/logos (atau public/storage/logos)
            $destinationPath = public_path('storage/logos');
            
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $file->move($destinationPath, $fileName);

            return redirect()->back()->with('success', 'Logo berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah logo.');
    }
    public function update_hero_slide_metadata(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tb_gambar_home,id_gambar_home',
            'title' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $slide = \App\Models\Gambar\Slides\Slides::find($request->id);
        if ($slide) {
            $slide->title = $request->title;
            $slide->deskripsi = $request->deskripsi;
            $slide->save();

            return redirect()->back()->with('success', 'Metadata slide berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Slide tidak ditemukan.');
    }

    public function upload_hero_slide(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');
            $fileName = time() . str_shuffle("abcdefghijklmnopqrstuvwxyz") . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('GambarSlides');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $file->move($destinationPath, $fileName);

            // Save to tb_gambar_home
            $slide = new \App\Models\Gambar\Slides\Slides;
            $slide->image_name = $fileName;
            $slide->title = 'Hero Slide';
            $slide->deskripsi = '';
            $slide->alt = 'Hero Slide';
            $slide->url_path = '/GambarSlides/' . $fileName;
            $slide->aktif = '1';
            $slide->save();

            return redirect()->back()->with('success', 'Slide Hero berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah slide.');
    }

    public function delete_hero_slide(Request $request)
    {
        $slide = \App\Models\Gambar\Slides\Slides::find($request->id);
        if ($slide) {
            // Delete physical file
            $filePath = public_path('GambarSlides/' . $slide->image_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $slide->delete();
        }
        return redirect()->back()->with('success', 'Slide berhasil dihapus.');
    }

    public function update_bank_accounts(Request $request)
    {
        $request->validate([
            'bank_bca_number' => 'nullable|string|max:50',
            'bank_bca_name' => 'nullable|string|max:100',
            'bank_bni_number' => 'nullable|string|max:50',
            'bank_bni_name' => 'nullable|string|max:100',
            'bank_mandiri_number' => 'nullable|string|max:50',
            'bank_mandiri_name' => 'nullable|string|max:100',
            'bank_bri_number' => 'nullable|string|max:50',
            'bank_bri_name' => 'nullable|string|max:100',
        ]);

        $settingsPath = storage_path('app/settings.json');
        $settings = [];
        if (File::exists($settingsPath)) {
            $settings = json_decode(File::get($settingsPath), true);
        }

        // Update bank account data
        $settings['bank_bca_number'] = $request->bank_bca_number;
        $settings['bank_bca_name'] = $request->bank_bca_name;
        $settings['bank_bni_number'] = $request->bank_bni_number;
        $settings['bank_bni_name'] = $request->bank_bni_name;
        $settings['bank_mandiri_number'] = $request->bank_mandiri_number;
        $settings['bank_mandiri_name'] = $request->bank_mandiri_name;
        $settings['bank_bri_number'] = $request->bank_bri_number;
        $settings['bank_bri_name'] = $request->bank_bri_name;

        File::put($settingsPath, json_encode($settings));

        return redirect()->back()->with('success', 'Rekening bank berhasil diperbarui!');
    }
}

