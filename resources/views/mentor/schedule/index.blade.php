@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Atur Jadwal Sesi')

@section('header_stats')
    {{-- Statistik Sederhana --}}
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sesi Terjadwal</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $sessions->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="ni ni-calendar-grid-58"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Permintaan Pending</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $pendingRequests->count() }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="ni ni-bell-55"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- ALERT SYSTEM --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Harap periksa input Anda. Pastikan waktu sesi valid.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        {{-- BAGIAN 1: DAFTAR SESI --}}
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Jadwal Sesi Anda</h3>
                        </div>
                        <div class="col text-right">
                            {{-- Tombol CREATE --}}
                            <button type="button" class="btn btn-sm btn-success" 
                                    data-toggle="modal" 
                                    data-target="#modal-sesi" 
                                    data-mode="create">
                                <i class="fas fa-plus"></i> Buat Sesi Baru
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Topik Sesi</th>
                                <th scope="col">Waktu Mulai</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                            <tr>
                                <th scope="row">
                                    {{ $session->title }}
                                </th>
                                <td>
                                    {{ $session->start_time->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $session->type == '1on1' ? 'primary' : 'info' }}">
                                        {{ $session->type == '1on1' ? 'Private' : 'Group' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-dot">
                                        <i class="bg-{{ $session->status == 'scheduled' ? 'success' : 'warning' }}"></i> {{ ucfirst($session->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            {{-- Tombol Edit --}}
                                            <a class="dropdown-item" href="#" 
                                               data-toggle="modal" 
                                               data-target="#modal-sesi"
                                               data-mode="edit"
                                               data-id="{{ $session->id }}"
                                               data-title="{{ $session->title }}"
                                               data-type="{{ $session->type }}"
                                               data-start="{{ $session->start_time->format('Y-m-d\TH:i') }}"
                                               data-end="{{ $session->end_time->format('Y-m-d\TH:i') }}"
                                               data-url="{{ $session->url_meeting }}"
                                               data-description="{{ $session->description }}">
                                                Edit Sesi
                                            </a>
                                            
                                            {{-- Tombol Delete --}}
                                            <a class="dropdown-item text-danger" href="#" onclick="if(confirm('Yakin ingin menghapus sesi ini?')) { document.getElementById('delete-form-{{ $session->id }}').submit(); }">
                                                Hapus Sesi
                                            </a>
                                            <form id="delete-form-{{ $session->id }}" action="{{ route('mentor.sessions.destroy', $session->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada sesi yang dijadwalkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL SESI (SINGLE MODAL: CREATE & EDIT) --}}
    <div class="modal fade" id="modal-sesi" tabindex="-1" role="dialog" aria-labelledby="modal-sesi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Buat Sesi Belajar Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <form id="form-sesi" action="{{ route('mentor.sessions.store') }}" method="POST">
                    @csrf
                    <div id="method-spoofing"></div> {{-- Tempat inject @method('PUT') --}}
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Judul Sesi</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Contoh: Bedah Esai LPDP">
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Tipe Sesi</label>
                            <select class="form-control" name="type" id="type">
                                <option value="1on1">1-on-1 (Private)</option>
                                <option value="group">Group (Webinar)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Waktu Mulai</label>
                                    <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Waktu Selesai</label>
                                    <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="url_meeting">Link Meeting (Zoom/GMeet)</label>
                            <input type="url" class="form-control" id="url_meeting" name="url_meeting" placeholder="https://...">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi Sesi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('js')
    <script>
        $('#modal-sesi').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var modal = $(this);
            
            if (button.data('mode') === 'edit') {
                // --- MODE EDIT ---
                var id = button.data('id');
                var updateRoute = '{{ route("mentor.sessions.update", ":id") }}';
                
                modal.find('.modal-title').text('Edit Sesi: ' + button.data('title'));
                modal.find('form').attr('action', updateRoute.replace(':id', id));
                modal.find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">');
                
                // Isi Form
                modal.find('#title').val(button.data('title'));
                modal.find('#type').val(button.data('type'));
                modal.find('#start_time').val(button.data('start'));
                modal.find('#end_time').val(button.data('end'));
                modal.find('#url_meeting').val(button.data('url'));
                modal.find('#description').val(button.data('desc'));
                
            } else {
                // --- MODE CREATE ---
                modal.find('.modal-title').text('Buat Sesi Belajar Baru');
                modal.find('form').attr('action', '{{ route("mentor.sessions.store") }}');
                modal.find('#method-spoofing').html('');
                
                // Reset Form
                modal.find('form').trigger('reset');
            }
        });

        // Tampilkan modal lagi jika ada error validasi
        @if ($errors->any())
            $('#modal-sesi').modal('show');
        @endif
    </script>
    @endpush
@endsection