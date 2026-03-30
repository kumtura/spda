@extends('admin.layout.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Objek Wisata</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('administrator/objek_wisata/update/'.$objek->id_objek_wisata) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Nama Objek Wisata <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_objek" class="form-control" value="{{ $objek->nama_objek }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi" class="form-control" rows="4" required>{{ $objek->deskripsi }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control" rows="2" required>{{ $objek->alamat }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Harga Tiket (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="harga_tiket" class="form-control" value="{{ $objek->harga_tiket }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kapasitas Harian</label>
                                            <input type="number" name="kapasitas_harian" class="form-control" value="{{ $objek->kapasitas_harian }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jam Buka</label>
                                            <input type="time" name="jam_buka" class="form-control" value="{{ $objek->jam_buka }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Jam Tutup</label>
                                            <input type="time" name="jam_tutup" class="form-control" value="{{ $objek->jam_tutup }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Foto Objek Wisata</label>
                                    @if($objek->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/wisata/'.$objek->foto) }}" alt="{{ $objek->nama_objek }}" 
                                            class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                    @endif
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="text-muted">Max 2MB, format: JPG, PNG. Kosongkan jika tidak ingin mengubah foto.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update
                            </button>
                            <a href="{{ url('administrator/objek_wisata') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
