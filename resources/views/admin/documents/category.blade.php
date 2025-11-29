@extends('admin.layouts.app')

@section('title', 'Manage Document Categories')

@section('header_stats')
    <div class="row">
        {{-- Di sini kamu bisa mengganti angka hardcoded (10, 1, 1) dengan variabel dari Controller, misalnya: {{ $totalCategories }} --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Kategori Dokumen</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $categories->count() }}</span>
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

    {{-- ALERT SUKSES DARI STORE/UPDATE/DESTROY --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-inner--text">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    {{-- ALERT GAGAL VALIDASI (JIKA VALIDASI GAGAL DARI STORE/UPDATE) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-support-16"></i></span>
            <span class="alert-inner--text">Gagal menyimpan kategori. Silakan cek form Anda dan coba lagi.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
                                <th scope="col">Dibuat Pada</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- START PERULANGAN DATA KATEGORI --}}
                            @forelse ($categories as $category)
                            <tr>
                                <th scope="row">
                                    <span class="mb-0 text-sm">{{ $category->name }}</span>
                                </th>
                                <td>
                                    {{ $category->created_at->format('d M Y') }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            
                                            {{-- Tombol Edit (Akan memunculkan modal edit) --}}
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-edit-kategori" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                                Edit Kategori
                                            </a>
                                            
                                            {{-- Tombol Delete --}}
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $category->id }}').submit();">
                                                Hapus
                                            </a>
                                            
                                            {{-- Form Delete (Hidden) --}}
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.document.categories.destroy', $category) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada kategori dokumen yang ditambahkan.</td>
                            </tr>
                            @endforelse
                            {{-- END PERULANGAN DATA KATEGORI --}}
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        {{-- Laravel Pagination links (jika kamu menggunakan ->paginate() di Controller) --}}
                        {{-- {{ $categories->links() }} --}}
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL TAMBAH KATEGORI (CREATE) --}}
    <div class="modal fade" id="modal-tambah-kategori" tabindex="-1" role="dialog" aria-labelledby="modal-tambah-kategori" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-default">Tambah Kategori Dokumen Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <form action="{{ route('admin.document.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        
                        {{-- Jika validasi gagal, old('name') akan terisi --}}
                        <div class="form-group">
                            <label for="namaKategori">Nama Kategori Dokumen</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="namaKategori" 
                                   name="name" 
                                   placeholder="Contoh: Template Surat Rekomendasi" 
                                   value="{{ old('name') }}">

                            {{-- Menampilkan Error Validasi --}}
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
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
    
    {{-- MODAL EDIT KATEGORI (UPDATE) --}}
    <div class="modal fade" id="modal-edit-kategori" tabindex="-1" role="dialog" aria-labelledby="modal-edit-kategori" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-edit">Edit Kategori Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                {{-- Form action akan diisi oleh JavaScript saat modal dibuka --}}
                <form id="form-edit-kategori" action="" method="POST">
                    @csrf
                    @method('PUT') {{-- Wajib untuk method PUT/PATCH --}}
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editNamaKategori">Nama Kategori Dokumen</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="editNamaKategori" 
                                   name="name" 
                                   placeholder="Nama Kategori" 
                                   value="{{ old('name') }}">
                            
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS KHUSUS --}}
    @push('js')
        <script>
            // 1. Tampilkan Modal Create jika Validasi Gagal (untuk field 'name')
            @if($errors->any())
                // Cek apakah error berasal dari form CREATE (kita asumsikan error tanpa ID adalah CREATE)
                // Ini adalah cara sederhana untuk menampilkan modal CREATE saat validasi gagal
                @if(Request::isMethod('POST') && !Request::route('document_category'))
                    $('#modal-tambah-kategori').modal('show');
                @endif
            @endif
        
            // 2. Logika Modal Edit (Mengambil data dan mengisi form action)
            $('#modal-edit-kategori').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var id = button.data('id'); 
                var name = button.data('name');
                
                var modal = $(this);
                
                // Isi form field dengan data yang ada
                modal.find('.modal-body #editNamaKategori').val(name);
                
                // Ubah action form ke route update yang benar
                // route('admin.document.categories.update', [id])
                var updateRoute = '{{ route("admin.document.categories.update", ":id") }}';
                updateRoute = updateRoute.replace(':id', id);
                
                modal.find('form').attr('action', updateRoute);
            });
        </script>
    @endpush
@endsection