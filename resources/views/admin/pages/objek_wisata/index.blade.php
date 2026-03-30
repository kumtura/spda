@extends('admin.layout.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Objek Wisata</h4>
                    <a href="{{ url('administrator/objek_wisata/create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Objek Wisata
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Objek</th>
                                    <th>Alamat</th>
                                    <th>Harga Tiket</th>
                                    <th>Jam Operasional</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($objekWisata as $objek)
                                <tr>
                                    <td>
                                        @if($objek->foto)
                                        <img src="{{ asset('storage/wisata/'.$objek->foto) }}" alt="{{ $objek->nama_objek }}" 
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                        <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $objek->nama_objek }}</strong>
                                    </td>
                                    <td>{{ $objek->alamat }}</td>
                                    <td>Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}</td>
                                    <td>
                                        @if($objek->jam_buka && $objek->jam_tutup)
                                        {{ $objek->jam_buka }} - {{ $objek->jam_tutup }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($objek->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ url('administrator/objek_wisata/edit/'.$objek->id_objek_wisata) }}" 
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ url('administrator/objek_wisata/toggle/'.$objek->id_objek_wisata) }}" 
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-toggle-on"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $objek->id_objek_wisata }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada objek wisata</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus objek wisata ini?')) {
        window.location.href = '{{ url("administrator/objek_wisata/delete") }}/' + id;
    }
}
</script>
@endsection
