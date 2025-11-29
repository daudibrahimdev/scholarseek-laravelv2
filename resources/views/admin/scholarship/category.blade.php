@extends('admin.layouts.app')

@section('title', 'Manage Scholarship Categories')

@section('header_stats')
    {{-- Ambil jumlah total kategori aktif --}}
    @php
        $totalCategories = $categories->count();
        $activeCategories = $categories->where('is_active', true)->count();
    @endphp

    <div class="row">
        {{-- Card Total Kategori Unik --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Kategori Unik</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalCategories }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-cubes"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> ...</span>
                        <span class="text-nowrap">Kategori Baru Tahun Ini</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card Kategori Aktif --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Kategori Aktif</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $activeCategories }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Siap digunakan di Beasiswa</span>
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card Bidang Ilmu Populer (Placeholder) --}}
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Bidang Ilmu Populer</h5>
                            <span class="h2 font-weight-bold mb-0">...</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-flask"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Teknik, Kesehatan, Bisnis, dll.</span>
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
    
    {{-- ALERT GAGAL VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-support-16"></i></span>
            <span class="alert-inner--text">Gagal menyimpan kategori. Silakan periksa formulir Anda.</span>
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
                            <h3 class="mb-0">Tabel Kategori Beasiswa</h3>
                        </div>
                        <div class="col text-right">
                            {{-- Tombol CREATE - Tambah mode="create" --}}
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-kategori" data-mode="create">
                                <i class="fas fa-plus"></i> Tambah Kategori Baru
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Status</th>
                                <th scope="col">Dibuat Pada</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                            <tr>
                                <th scope="row">
                                    <span class="mb-0 text-sm">{{ $category->name }}</span>
                                </th>
                                <td>
                                    {{-- Kolom slug --}}
                                    <span class="text-muted text-sm">{{ $category->slug }}</span>
                                </td>
                                <td>
                                    {{-- Status aktif/non-aktif --}}
                                    <span class="badge badge-dot">
                                        <i class="bg-{{ $category->is_active ? 'success' : 'danger' }}"></i> {{ $category->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $category->created_at->format('d M Y') }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            
                                            {{-- Tombol EDIT (Membawa data-attributes) --}}
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-kategori"
                                               data-mode="edit" 
                                               data-id="{{ $category->id }}"
                                               data-name="{{ $category->name }}"
                                               data-description="{{ $category->description }}"
                                               data-is-active="{{ $category->is_active ? 1 : 0 }}">
                                                <i class="fas fa-edit text-info"></i> Edit Kategori
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            
                                            {{-- Tombol Delete --}}
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $category->id }}').submit();">
                                                <i class="fas fa-trash text-danger"></i> Hapus
                                            </a>
                                            
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.scholarship.categories.destroy', $category) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada kategori beasiswa yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    {{-- Pagination Links --}}
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL UTAMA (SINGLE MODAL UNTUK CREATE DAN EDIT) --}}
    <div class="modal fade" id="modal-kategori" tabindex="-1" role="dialog" aria-labelledby="modal-kategori" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-default">Tambah Kategori Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                {{-- Form action akan diisi dinamis oleh JS --}}
                <form id="form-kategori" action="{{ route('admin.scholarship.categories.store') }}" method="POST">
                    @csrf
                    <div id="method-spoofing"></div> {{-- Container untuk @method('PUT') --}}
                    {{-- Hidden field untuk ID Category --}}
                    <input type="hidden" id="category_id_field" name="category_id" value=""> 

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Kategori (Contoh: S1, Dalam Negeri, Bisnis)</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama kategori">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi Kategori</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Deskripsi singkat kategori...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="custom-control custom-checkbox mt-3">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Aktifkan Kategori (Tampilkan di Frontend)</label>
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
    
    {{-- SCRIPTS KHUSUS --}}
    @push('js')
        <script>
            // Logika Modal Multifungsi (Edit/Create)
            $('#modal-kategori').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var modal = $(this);
                
                // Clear validation errors and form state
                modal.find('.text-danger').remove();
                modal.find('.is-invalid').removeClass('is-invalid');

                // Cek Mode: Edit atau Create
                if (button.data('mode') === 'edit') {
                    // --- MODE EDIT ---
                    var id = button.data('id'); 
                    var name = button.data('name');
                    var description = button.data('description');
                    var isActive = button.data('is-active'); // 1 atau 0

                    var updateRoute = '{{ route("admin.scholarship.categories.update", ":id") }}';

                    modal.find('.modal-title').text('Edit Kategori: ' + name);
                    modal.find('form').attr('action', updateRoute.replace(':id', id));
                    // Tambahkan method spoofing (PUT)
                    modal.find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">'); 

                    // Isi Form
                    modal.find('#name').val(name);
                    modal.find('#description').val(description);
                    modal.find('#is_active').prop('checked', isActive == 1);
                    modal.find('#category_id_field').val(id); // ISI HIDDEN ID INI

                } else { 
                    // --- MODE CREATE ---
                    modal.find('.modal-title').text('Tambah Kategori Baru');
                    modal.find('form').attr('action', '{{ route("admin.scholarship.categories.store") }}');
                    modal.find('#method-spoofing').html(''); 
                    
                    // Bersihkan form (jika tidak ada old input)
                    if (!'{{ old('name') }}') {
                        modal.find('form').trigger('reset');
                        modal.find('#is_active').prop('checked', true); // Default Aktif
                    }
                    modal.find('#category_id_field').val(''); // KOSONGKAN HIDDEN ID
                }
            });

            // Tampilkan Modal Saat Validasi Gagal (Maintain Form State)
            @if ($errors->any())
                // Ambil ID dari route parameter jika validasi gagal di mode EDIT
                var categoryId = '{{ Request::route("scholarship_category") ? Request::route("scholarship_category")->id : 0 }}';

                if (categoryId > 0) {
                     var updateRoute = '{{ route("admin.scholarship.categories.update", ":id") }}';
                     updateRoute = updateRoute.replace(':id', categoryId);

                     $('#modal-kategori').find('form').attr('action', updateRoute);
                     $('#modal-kategori').find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">');
                     $('#modal-kategori').find('.modal-title').text('Edit Kategori: Gagal Validasi');
                     
                     // Pastikan checkbox is_active diisi ulang dengan old() input
                     if ('{{ old("is_active") }}' == '1') {
                         $('#is_active').prop('checked', true);
                     } else {
                         $('#is_active').prop('checked', false);
                     }
                }
                
                // Tampilkan modal
                $('#modal-kategori').modal('show');
            @endif
        </script>
    @endpush
@endsection