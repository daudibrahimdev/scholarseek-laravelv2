@extends('admin.layouts.app')

@section('title', 'Manage Scholarships')

@section('header_stats')
    {{-- Ambil jumlah total dokumen --}}
    @php
        $totalScholarships = $scholarships->count();
        $activeCategories = $categories->where('is_active', true)->count();
        $soonDeadline = $scholarships->where('deadline', '>', now())->count();
    @endphp

    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Beasiswa</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $totalScholarships }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="ni ni-briefcase-24"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $soonDeadline }}</span>
                        <span class="text-nowrap">Masih Membuka Pendaftaran</span>
                    </p>
                </div>
            </div>
        </div>
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
                                <i class="ni ni-tag"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Total Kategori yang Bisa Dipilih</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Deadline Terdekat</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $scholarships->where('deadline', '>', now())->min('deadline')?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-nowrap">Tenggat waktu terdekat</span>
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
            <span class="alert-inner--text">Gagal menyimpan beasiswa. Silakan periksa semua input Anda.</span>
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
                            <h3 class="mb-0">Daftar Beasiswa Aktif</h3>
                        </div>
                        <div class="col text-right">
                            {{-- Tombol CREATE --}}
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-scholarship" data-mode="create">
                                <i class="fas fa-plus"></i> Tambah Beasiswa Baru
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Beasiswa</th>
                                <th scope="col">Provider</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($scholarships as $sch)
                            <tr>
                                <th scope="row">
                                    <span class="mb-0 text-sm">{{ $sch->title }}</span>
                                    <p class="text-muted text-sm mt-1 mb-0">{{ $sch->link_url }}</p>
                                </th>
                                <td>
                                    {{ $sch->provider }}
                                </td>
                                <td>
                                    {{-- Loop Kategori --}}
                                    @foreach ($sch->categories as $category)
                                        <span class="badge badge-primary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $sch->deadline->format('d M Y') }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            
                                            {{-- Tombol Edit (Membawa data-attributes) --}}
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-scholarship"
                                               data-mode="edit" 
                                               data-id="{{ $sch->id }}"
                                               data-title="{{ $sch->title }}"
                                               data-provider="{{ $sch->provider }}"
                                               data-description="{{ $sch->description }}"
                                               data-start-date="{{ $sch->start_date->format('Y-m-d') }}"
                                               data-deadline="{{ $sch->deadline->format('Y-m-d') }}"
                                               data-link-url="{{ $sch->link_url }}"
                                               data-categories="{{ $sch->categories->pluck('id')->toJson() }}"> {{-- Mengirim ID Kategori dalam format JSON --}}
                                                <i class="fas fa-edit text-info"></i> Edit Beasiswa
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            
                                            {{-- Tombol Delete --}}
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $sch->id }}').submit();">
                                                <i class="fas fa-trash text-danger"></i> Hapus
                                            </a>
                                            
                                            <form id="delete-form-{{ $sch->id }}" action="{{ route('admin.scholarship.destroy', $sch) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada beasiswa yang ditambahkan.</td>
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
    <div class="modal fade" id="modal-scholarship" tabindex="-1" role="dialog" aria-labelledby="modal-scholarship" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-default">Tambah Beasiswa Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                {{-- Form action akan diisi dinamis oleh JS --}}
                <form id="form-scholarship" action="{{ route('admin.scholarship.store') }}" method="POST">
                    @csrf
                    <div id="method-spoofing"></div> {{-- Container untuk @method('PUT') --}}
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Judul Beasiswa</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Beasiswa LPDP Tahap 1">
                                    @error('title')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provider">Provider/Penyedia</label>
                                    <input type="text" class="form-control @error('provider') is-invalid @enderror" id="provider" name="provider" value="{{ old('provider') }}" placeholder="Contoh: LPDP, Chevening">
                                    @error('provider')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
    <label>Kategori Beasiswa
        {{-- TOMBOL LINK CEK KATEGORI --}}
        <a href="{{ route('admin.scholarship.categories.index') }}" target="_blank" 
           class="text-info ml-2" data-toggle="tooltip" title="Kelola Kategori">
            <i class="fas fa-external-link-alt"></i> </a>
    </label>
    
    <div class="row ml-1" id="category-checkboxes">
        {{-- Loop Category Checkboxes --}}
        @forelse ($categories as $category)
            <div class="custom-control custom-checkbox col-md-4 mb-2">
                <input type="checkbox" class="custom-control-input category-check" 
                        id="cat_{{ $category->id }}" 
                        name="category_ids[]" 
                        value="{{ $category->id }}" 
                        {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                <label class="custom-control-label" for="cat_{{ $category->id }}">{{ $category->name }}</label>
            </div>
        @empty
            <p class="text-danger">Harap buat Kategori Beasiswa terlebih dahulu. 
                <a href="{{ route('admin.scholarship.categories.index') }}" target="_blank">Buat sekarang</a>.
            </p>
        @endforelse
    </div>
    
    @error('category_ids')
        <div class="text-danger mt-1">Anda wajib memilih minimal satu kategori.</div>
    @enderror
</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai Pendaftaran</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deadline">Batas Akhir Pendaftaran</label>
                                    <input type="date" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline') }}">
                                    @error('deadline')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="link_url">Link Resmi Beasiswa</label>
                            <input type="url" class="form-control @error('link_url') is-invalid @enderror" id="link_url" name="link_url" value="{{ old('link_url') }}" placeholder="https://beasiswa-resmi.com">
                            @error('link_url')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi Lengkap</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Jelaskan syarat, cakupan, dan kriteria...">{{ old('description') }}</textarea>
                            @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Beasiswa</button>
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
            $('#modal-scholarship').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var modal = $(this);
                
                // Clear validation errors and form state
                modal.find('.text-danger').remove();
                modal.find('.is-invalid').removeClass('is-invalid');

                // Reset semua checkboxes (default mode: create)
                modal.find('.category-check').prop('checked', false);

                // Cek Mode: Edit atau Create
                if (button.data('mode') === 'edit') {
                    // --- MODE EDIT ---
                    
                    var schId = button.data('id'); 
                    var schTitle = button.data('title');
                    var schProvider = button.data('provider');
                    var schDesc = button.data('description');
                    var schDeadline = button.data('deadline');
                    var schStart = button.data('start-date');
                    var schLink = button.data('link-url');
                    var schCategories = button.data('categories'); // Array of category IDs (JSON)
                    var updateRoute = '{{ route("admin.scholarship.update", ":id") }}';

                    modal.find('.modal-title').text('Edit Beasiswa: ' + schTitle);
                    modal.find('form').attr('action', updateRoute.replace(':id', schId));
                    modal.find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">'); 

                    // Isi Form
                    modal.find('#title').val(schTitle);
                    modal.find('#provider').val(schProvider);
                    modal.find('#description').val(schDesc);
                    modal.find('#start_date').val(schStart);
                    modal.find('#deadline').val(schDeadline);
                    modal.find('#link_url').val(schLink);
                    
                    // Isi Checkboxes Kategori (LOGIKA MANY-TO-MANY)
                    if (schCategories && Array.isArray(schCategories)) {
                        schCategories.forEach(function(catId) {
                            modal.find('#cat_' + catId).prop('checked', true);
                        });
                    }

                } else { 
                    // --- MODE CREATE ---
                    
                    modal.find('.modal-title').text('Tambah Beasiswa Baru');
                    modal.find('form').attr('action', '{{ route("admin.scholarship.store") }}');
                    modal.find('#method-spoofing').html(''); 
                    
                    // Bersihkan form (jika tidak ada old input)
                    if (!'{{ old('title') }}' && !'{{ $errors->any() }}') {
                        modal.find('form').trigger('reset');
                    }
                }
            });

            // Tampilkan Modal Saat Validasi Gagal (Maintain Form State)
            @if ($errors->any())
                $('#modal-scholarship').modal('show');
                
                // Jika validasi gagal dari mode edit, isi kembali method spoofing
                @if(Request::isMethod('PUT') || Request::isMethod('PATCH'))
                    $('#modal-scholarship').find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">');
                @endif
            @endif
        </script>
    @endpush
@endsection