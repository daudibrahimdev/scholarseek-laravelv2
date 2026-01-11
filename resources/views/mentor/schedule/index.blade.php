@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Kelola Jadwal Sesi')

@section('header_stats')
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 shadow border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sesi Aktif & Mendatang</h5>
                            <span class="h2 font-weight-bold mb-0 text-primary">{{ $scheduledCount ?? 0 }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card card-stats mb-4 mb-xl-0 shadow border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Sesi Selesai</h5>
                            <span class="h2 font-weight-bold mb-0 text-success">{{ $finishedCount ?? 0 }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-check-double"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- ALERT SUKSES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- ALERT ERROR UMUM --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <span class="alert-text">{{ session('error') }}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- ERROR VALIDASI (Agar terlihat jika modal gagal terbuka) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <span class="alert-text"><strong>Perhatian!</strong> Ada kesalahan pada input jadwal Anda. Silakan cek kembali form.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow border-0">
                <div class="card-header border-0 bg-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0 text-primary">Daftar Sesi Aktif</h3>
                            <small class="text-muted">Sesi otomatis pindah ke Riwayat setelah waktu selesai terlampaui.</small>
                        </div>
                        <div class="col text-right">
                            <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#modal-sesi" data-mode="create">
                                <i class="fas fa-plus mr-1"></i> Buat Sesi Baru
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
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingSessions ?? $sessions as $session)
                            <tr>
                                <th scope="row" class="text-dark font-weight-600">{{ $session->title }}</th>
                                <td><i class="far fa-clock text-info mr-1"></i> {{ $session->start_time->format('d M Y, H:i') }}</td>
                                <td>
                                    <span class="badge badge-pill {{ $session->type == '1on1' ? 'badge-primary' : 'badge-info' }}">
                                        {{ $session->type == '1on1' ? 'Private' : 'Group' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = ['scheduled' => 'primary', 'ongoing' => 'warning', 'completed' => 'success', 'cancelled' => 'danger'];
                                        $color = $statusColors[$session->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-dot">
                                        <i class="bg-{{ $color }}"></i> <span class="status text-{{ $color }}">{{ ucfirst($session->status) }}</span>
                                    </span>
                                </td>
                                <td class="text-right">
                                    @if($session->status == 'scheduled' || $session->status == 'ongoing')
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown">
                                          <i class="fas fa-ellipsis-v text-primary"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                            @if($session->status == 'scheduled')
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-sesi" data-mode="edit"
                                               data-id="{{ $session->id }}" 
                                               data-title="{{ $session->title }}"
                                               data-type="{{ $session->type }}" 
                                               data-start="{{ $session->start_time->format('Y-m-d\TH:i') }}"
                                               data-end="{{ $session->end_time->format('Y-m-d\TH:i') }}" 
                                               data-url="{{ $session->url_meeting }}"
                                               data-description="{{ $session->description }}">
                                                <i class="fas fa-edit text-info mr-2"></i> Edit Sesi
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            @endif
                                            
                                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Batalkan sesi ini? Kuota akan dikembalikan ke mentee.')) { document.getElementById('cancel-form-{{ $session->id }}').submit(); }">
                                                <i class="fas fa-times-circle mr-2"></i> Batalkan Sesi
                                            </a>
                                            <form id="cancel-form-{{ $session->id }}" action="{{ route('mentor.sessions.cancel', $session->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                        <span class="text-muted small italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada sesi aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM --}}
    <div class="modal fade" id="modal-sesi" tabindex="-1" role="dialog" aria-labelledby="modal-sesi-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-secondary border-0">
                    <h5 class="modal-title text-primary font-weight-bold" id="modal-title">Buat Sesi Belajar Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                
                <form id="form-sesi" action="{{ route('mentor.sessions.store') }}" method="POST">
                    @csrf
                    <div id="method-spoofing"></div>
                    
                    <div class="modal-body bg-secondary">
                        {{-- ALERT ERROR DI DALAM MODAL (SUPAYA LO TAU KENAPA GA MASUK DB) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong class="d-block mb-1">Gagal Menyimpan!</strong>
                                <ul class="mb-0 pl-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-control-label">Judul Sesi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-alternative" id="title" name="title" required value="{{ old('title') }}" placeholder="Contoh: Bedah Essay LPDP">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Tipe Sesi <span class="text-danger">*</span></label>
                            <select class="form-control form-control-alternative" name="type" id="type">
                                <option value="1on1" {{ old('type') == '1on1' ? 'selected' : '' }}>1-on-1 (Private)</option>
                                <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>Group (Umum)</option>
                            </select>
                        </div>

                        <div class="form-group" id="mentee-assign-field">
                            <label class="form-control-label">Assign Mentee <span class="text-danger">*</span></label>
                            <select class="form-control form-control-alternative" name="user_package_id" id="user_package_id">
                                <option value="">-- Pilih Mentee --</option>
                                @isset($assignedPackages)
                                    @foreach ($assignedPackages as $package)
                                        <option value="{{ $package->id }}" {{ old('user_package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->mentee->name }} (Sisa: {{ $package->remaining_quota }})
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            <small class="text-muted font-italic">Hanya muncul untuk mentee yang sudah membeli paket.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control form-control-alternative" id="start_time" name="start_time" required value="{{ old('start_time') }}">
                                    <small class="text-muted">Harus waktu mendatang.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control form-control-alternative" id="end_time" name="end_time" required value="{{ old('end_time') }}">
                                    <small class="text-muted">Harus setelah waktu mulai.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Link Meeting</label>
                            <input type="url" class="form-control form-control-alternative" id="url_meeting" name="url_meeting" value="{{ old('url_meeting') }}" placeholder="https://zoom.us/...">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Deskripsi</label>
                            <textarea class="form-control form-control-alternative" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-secondary border-top-0">
                        <button type="button" class="btn btn-link text-muted ml-auto" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Fungsi untuk toggle field Mentee
        function toggleMenteeField() {
            var type = $('#type').val();
            if (type === '1on1') {
                $('#mentee-assign-field').show();
                $('#user_package_id').attr('required', 'required');
            } else {
                $('#mentee-assign-field').hide();
                $('#user_package_id').removeAttr('required');
                $('#user_package_id').val(''); // Reset selection
            }
        }

        // Jalankan saat load (penting jika ada error validasi dan halaman reload)
        toggleMenteeField();

        // Jalankan saat dropdown berubah
        $('#type').on('change', function() {
            toggleMenteeField();
        });

        // AUTO-OPEN MODAL JIKA ADA ERROR
        // Ini kuncinya bro! Kalau validasi gagal, modal kebuka lagi otomatis
        @if ($errors->any())
            $('#modal-sesi').modal('show');
        @endif

        // Handler saat tombol modal diklik (Create/Edit)
        $('#modal-sesi').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            
            // Jika modal dibuka otomatis karena error (bukan diklik tombol), abaikan reset
            if (!button.length) return; 

            var modal = $(this);
            var mode = button.data('mode');
            
            if (mode === 'edit') {
                modal.find('.modal-title').text('Edit Sesi');
                modal.find('form').attr('action', '{{ url("mentor/sessions") }}/' + button.data('id'));
                modal.find('#method-spoofing').html('<input type="hidden" name="_method" value="PUT">');
                
                modal.find('#title').val(button.data('title'));
                modal.find('#type').val(button.data('type')).change(); // Trigger change biar mentee toggle jalan
                modal.find('#start_time').val(button.data('start'));
                modal.find('#end_time').val(button.data('end'));
                modal.find('#url_meeting').val(button.data('url'));
                modal.find('#description').val(button.data('description'));
                
                // Di mode edit, kita sembunyikan assign mentee agar kuota tidak rusak/ganda
                $('#mentee-assign-field').hide(); 
                
            } else {
                modal.find('.modal-title').text('Buat Sesi Baru');
                modal.find('form').attr('action', '{{ route("mentor.sessions.store") }}');
                modal.find('#method-spoofing').html('');
                
                // Reset form manual biar bersih
                modal.find('form')[0].reset();
                $('#type').val('1on1').change(); // Default ke 1on1
            }
        });
    });
</script>
@endpush