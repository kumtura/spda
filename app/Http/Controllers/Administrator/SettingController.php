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
        return view('admin.pages.settings.index');
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
    public function update_gallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            $destinationPath = public_path('storage/gallery');
            
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $file->move($destinationPath, $fileName);

            return redirect()->back()->with('success', 'Foto Galeri berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }
}
