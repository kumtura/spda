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
    public function upload_hero_slide(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hero_title' => 'nullable|string|max:255',
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
            $slide->title = $request->hero_title ?? 'Hero Slide';
            $slide->deskripsi = $request->hero_title ?? '';
            $slide->alt = $request->hero_title ?? '';
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
}
