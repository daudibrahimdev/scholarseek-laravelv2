@extends('admin.layouts.app')

@section('title', 'Manage Document Categories')

@section('header_stats')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Kategori Dokumen</h5>
                            <span class="h2 font-weight-bold mb-0">10</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 1</span>
                        <span class="text-nowrap">Kategori Baru Bulan Ini</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Kategori Terpopuler</h5>
                            <span class="h2 font-weight-bold mb-0">Contoh Esai</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-fire"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Digunakan pada 12 dokumen</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Kategori Non-Aktif</h5>
                            <span class="h2 font-weight-bold mb-0">1</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-eye-slash"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Dapat diaktifkan kembali</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tabel Kategori Dokumen</h3>
                        </div>
                        <div class="col text-right">
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-tambah-kategori">
                                <i class="fas fa-plus"></i> Tambah Kategori Dokumen
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Jumlah Dokumen</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <span class="mb-0 text-sm">Contoh Esai</span>
                                </th>
                                <td>
                                    Kumpulan esai terbaik dari awardee yang berhasil.
                                </td>
                                <td>
                                    12 Dokumen
                                </td>
                                <td>
                                    <span class="badge badge-dot">
                                        <i class="bg-success"></i> Aktif
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Edit Kategori</a>
                                            <a class="dropdown-item text-danger" href="#">Non-aktifkan</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-tambah-kategori" tabindex="-1" role="dialog" aria-labelledby="modal-tambah-kategori" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-default">Tambah Kategori Dokumen Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="namaKategori">Nama Kategori Dokumen</label>
                            <input type="text" class="form-control" id="namaKategori" name="name" placeholder="Contoh: Template Surat Rekomendasi">
                        </div>
                        <div class="form-group">
                            <label for="deskripsiKategori">Deskripsi Singkat</label>
                            <textarea class="form-control" id="deskripsiKategori" name="description" rows="3" placeholder="Jelaskan jenis dokumen yang termasuk dalam kategori ini..."></textarea>
                        </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
@endsection