<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\KategoriAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function index()
    {
        $agenda = Agenda::with('kategori')
            ->where('aktif', '1')
            ->orderBy('tanggal_agenda', 'desc')
            ->orderBy('waktu_agenda', 'asc') // Sort by time within the day
            ->get();
        $kategori = KategoriAgenda::where('aktif', '1')->orderBy('nama_kategori', 'asc')->get();
        return view('admin.pages.agenda.index', compact('agenda', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori_agenda' => 'required|exists:tb_kategori_agenda,id_kategori_agenda',
            'judul_agenda' => 'required|string|max:255',
            'deskripsi_agenda' => 'required|string',
            'tanggal_agenda' => 'required|date',
            'waktu_agenda' => 'nullable|string',
            'waktu_selesai_data' => 'nullable|string',
            'status_selesai' => 'required|in:fixed,selesai',
            'lokasi_agenda' => 'required|string|max:255',
            'status_agenda' => 'required|in:Draft,Publish',
            'foto_agenda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        $data['aktif'] = '1';

        if ($request->hasFile('foto_agenda')) {
            $file = $request->file('foto_agenda');
            $filename = time() . '_' . Str::slug($request->judul_agenda) . '.' . $file->getClientOriginalExtension();
            $path = 'storage/agenda';
            
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }
            
            $file->move(public_path($path), $filename);
            $data['foto_agenda'] = $filename;
        }

        Agenda::create($data);

        return redirect()->back()->with('success', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_agenda' => 'required|exists:tb_agenda,id_agenda',
            'id_kategori_agenda' => 'required|exists:tb_kategori_agenda,id_kategori_agenda',
            'judul_agenda' => 'required|string|max:255',
            'deskripsi_agenda' => 'required|string',
            'tanggal_agenda' => 'required|date',
            'waktu_agenda' => 'nullable|string',
            'waktu_selesai_data' => 'nullable|string',
            'status_selesai' => 'required|in:fixed,selesai',
            'lokasi_agenda' => 'required|string|max:255',
            'status_agenda' => 'required|in:Draft,Publish',
            'foto_agenda' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $agenda = Agenda::find($request->id_agenda);
        $data = $request->all();

        if ($request->hasFile('foto_agenda')) {
            // Delete old photo if exists
            if ($agenda->foto_agenda && file_exists(public_path('storage/agenda/' . $agenda->foto_agenda))) {
                unlink(public_path('storage/agenda/' . $agenda->foto_agenda));
            }

            $file = $request->file('foto_agenda');
            $filename = time() . '_' . Str::slug($request->judul_agenda) . '.' . $file->getClientOriginalExtension();
            $path = 'storage/agenda';
            
            $file->move(public_path($path), $filename);
            $data['foto_agenda'] = $filename;
        }

        $agenda->update($data);

        return redirect()->back()->with('success', 'Agenda berhasil diperbarui');
    }

    public function destroy($id)
    {
        $agenda = Agenda::find($id);
        if ($agenda) {
            $agenda->update(['aktif' => '0']);
            return redirect()->back()->with('success', 'Agenda berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Agenda tidak ditemukan');
    }

    public function toggle_status($id)
    {
        $agenda = Agenda::find($id);
        if ($agenda) {
            $newStatus = $agenda->status_agenda == 'Publish' ? 'Draft' : 'Publish';
            $agenda->update(['status_agenda' => $newStatus]);
            return redirect()->back()->with('success', 'Status agenda berhasil diubah menjadi ' . $newStatus);
        }
        return redirect()->back()->with('error', 'Agenda tidak ditemukan');
    }
}
