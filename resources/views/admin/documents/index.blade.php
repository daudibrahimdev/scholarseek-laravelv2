@extends('admin.layouts.app')

@section('title', 'Manage Documents')

@section('header_stats')
    {{-- Ambil jumlah total dokumen --}}
    @php
        $totalDocuments = $documents->count();
        $totalCategories = $categories->count();
    @endphp

    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Dokumen</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalDocuments }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ min(5, $totalDocuments) }}</span>
                        <span class="text-nowrap">Dokumen Terbaru</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ $categories->first() ? $categories->first()->name : 'Contoh Esai' }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $categories->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Total Kategori: {{ $totalCategories }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Upload Oleh Admin</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $documents->where('uploaded_by', Auth::id())->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Dokumen yang kamu upload</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- ALERT SUKSES & ERROR --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-inner--text">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="ni ni-support-16"></i></span>
            <span class="alert-inner--text">Gagal menyimpan dokumen. Silakan periksa formulir dan file yang diunggah.</span>
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
                            <h3 class="mb-0">Daftar Dokumen Informasional</h3>
                        </div>
                        <div class="col text-right">
                            {{-- Tombol CREATE - Tambah mode="create" --}}
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-dokumen" data-mode="create">
                                <i class="fas fa-upload"></i> Tambah Dokumen Baru
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama Dokumen</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Uploader</th>
                                <th scope="col">Tanggal Upload</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                            <tr>
                                <th scope="row">
                                    <span class="mb-0 text-sm">{{ $doc->title }}</span>
                                    <p class="text-muted text-sm mt-1 mb-0">{{ Str::limit($doc->description, 50) }}</p>
                                </th>
                                <td>
                                    <span class="badge badge-primary">{{ $doc->category->name }}</span>
                                </td>
                                <td>
                                    {{ $doc->uploader->name }}
                                </td>
                                <td>
                                    {{ $doc->created_at->format('d M Y') }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            
                                            {{-- Tombol Download/Preview --}}
                                            <a class="dropdown-item" href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                                                <i class="fas fa-download text-success"></i> Lihat & Unduh
                                            </a>
                                            
                                            {{-- Tombol EDIT - Tambah mode="edit" dan data-attributes --}}
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-dokumen"
                                               data-mode="edit" 
                                               data-id="{{ $doc->id }}"
                                               data-title="{{ $doc->title }}"
                                               data-description="{{ $doc->description }}"
                                               data-category-id="{{ $doc->document_category_id }}">
                                                <i class="fas fa-edit text-info"></i> Edit Dokumen
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            
                                            {{-- Form Delete --}}
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $doc->id }}').submit();">
                                                <i class="fas fa-trash text-danger"></i> Hapus
                                            </a>
                                            <form id="delete-form-{{ $doc->id }}" action="{{ route('admin.documents.destroy', $doc) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada dokumen yang diunggah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    {{-- <nav aria-label="...">...</nav> --}}
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL UTAMA (SINGLE MODAL UNTUK CREATE DAN EDIT) --}}
    {{-- ID Modal diubah menjadi modal-dokumen --}}
    <div class="modal fade" id="modal-dokumen" tabindex="-1" role="dialog" aria-labelledby="modal-dokumen" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-default">Tambah Dokumen Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="method-spoofing"></div> {{-- Tempat menampung @method('PUT') --}}
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Nama Dokumen</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Esai Terbaik LPDP - Jurusan Teknik">
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="document_category_id">Kategori Dokumen</label>
                                    <select class="form-control @error('document_category_id') is-invalid @enderror" id="document_category_id" name="document_category_id">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('document_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_category_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="beasiswaTerkait">Beasiswa Terkait (Opsional)</label>
                                    <input type="text" class="form-control" id="beasiswaTerkait" placeholder="Contoh: LPDP, Chevening, Global">
                                    <small class="form-text text-muted">Field ini belum terintegrasi ke DB, hanya placeholder.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_file">Unggah File Dokumen (PDF/DOCX/PPTX, Max 50MB)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('document_file') is-invalid @enderror" id="document_file" name="document_file" lang="in">
                                <label class="custom-file-label" id="document_file_label" for="document_file">Pilih file</label>
                            </div>
                            <small class="form-text text-muted">Format: PDF, DOCX, PPTX, XLS. Max 50MB.</small>
                            @error('document_file')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi Singkat</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Jelaskan isi dokumen secara ringkas...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- SCRIPTS KHUSUS --}}
    @push('js')
        <script>
            // 1. Logika JavaScript untuk menampilkan nama file pada input custom-file
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.custom-file-input').forEach(input => {
                    input.addEventListener('change', function(e) {
                        var fileName = e.target.files[0].name;
                        var nextSibling = e.target.nextElementSibling;
                        nextSibling.innerText = fileName;
                    });
                });
            });

            // 2. Logika Modal Multifungsi (Edit/Create)
            $('#modal-dokumen').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var modal = $(this);
                
                // Cek Mode: Edit atau Create
                if (button.data('mode') === 'edit') {
                    // --- MODE EDIT ---
                    
                    var docId = button.data('id'); 
                    var docTitle = button.data('title');
                    var docDesc = button.data('description');
                    var categoryId = button.data('category-id');
                    var updateRoute = '{{ route("admin.documents.update", ":id") }}';

                    // Ubah Judul Modal dan Aksi Form
                    modal.find('.modal-title').text('Edit Dokumen: ' + docTitle);
                    modal.find('form').attr('action', updateRoute.replace(':id', docId));
                    // Tambahkan method spoofing (PUT)
                    modal.find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">'); 

                    // Isi Form dengan Data Dokumen
                    modal.find('#title').val(docTitle);
                    modal.find('#document_category_id').val(categoryId);
                    modal.find('#description').val(docDesc);
                    
                    // Reset input file label
                    modal.find('.custom-file-label').text('Pilih file baru jika ingin diganti');

                } else { 
                    // --- MODE CREATE ---
                    
                    modal.find('.modal-title').text('Tambah Dokumen Baru');
                    modal.find('form').attr('action', '{{ route("admin.documents.store") }}');
                    modal.find('#method-spoofing').html(''); // Hapus method PUT
                    
                    // Bersihkan form
                    modal.find('form').trigger('reset');
                    modal.find('.custom-file-label').text('Pilih file'); 
                }
            });

            // 3. Tampilkan Modal Saat Validasi Gagal (untuk mempertahankan mode edit/create)
            @if ($errors->any())
                // Cek apakah error berasal dari form CREATE atau UPDATE
                @if(Request::isMethod('POST') && Request::routeIs('admin.documents.store'))
                    $('#modal-dokumen').modal('show');
                @endif
                
                // Tambahan: Agar modal Edit muncul jika error validasi datang dari PUT (Update)
                @if(Request::isMethod('PUT') || Request::isMethod('PATCH'))
                    // Karena kita tidak tahu ID mana yang gagal divalidasi, ini perlu penanganan spesifik
                    // Kita asumsikan error datang dari mode edit dan paksa modal buka
                    $('#modal-dokumen').modal('show');
                    
                    // Optional: Isi kembali form yang gagal di-update dengan old() values
                    var updateRoute = '{{ route("admin.documents.update", ":id") }}';
                    updateRoute = updateRoute.replace(':id', '{{ Request::route('document') ? Request::route('document')->id : 0 }}');
                    $('#modal-dokumen').find('form').attr('action', updateRoute);
                    $('#modal-dokumen').find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">'); 
                    $('#modal-dokumen').find('.modal-title').text('Edit Dokumen: Gagal Validasi');
                @endif
            @endif
        </script>
    @endpush
@endsection