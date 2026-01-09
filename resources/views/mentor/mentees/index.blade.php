@extends('layouts.mentor_master')

@section('sidebar')
    @include('mentor.partials.sidebar')
@endsection

@section('title', 'Daftar Mentee Terhubung')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
<style>
    .avatar-initial {
        width: 45px; height: 45px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.2rem;
    }
    .table td { vertical-align: middle !important; }
    .dropdown-item { cursor: pointer; }
    .action-icon { width: 20px; text-align: center; display: inline-block; margin-right: 10px; }
</style>
@endpush

@section('header_stats')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 shadow border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Mentee Aktif</h5>
                            <span class="h2 font-weight-bold mb-0 text-success">{{ $stats['total_active'] ?? 0 }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card card-stats mb-4 shadow border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Permintaan Baru</h5>
                            <span class="h2 font-weight-bold mb-0 text-warning">{{ $stats['total_pending'] ?? 0 }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card shadow border-0">
            <div class="card-header border-0 bg-white d-flex justify-content-between">
                <h3 class="mb-0 text-primary">Daftar Mentee Terhubung</h3>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle shadow-none" type="button" data-toggle="dropdown">
                        <i class="fas fa-filter mr-1"></i> Filter Status
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                        <a class="dropdown-item" href="{{ route('mentor.mentees.index') }}">Semua Status</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-success" href="{{ route('mentor.mentees.index', ['status' => 'active']) }}">Aktif</a>
                        <a class="dropdown-item text-warning" href="{{ route('mentor.mentees.index', ['status' => 'pending_approval']) }}">Menunggu</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Profil Mentee & Target</th>
                            <th scope="col">Paket Diambil</th>
                            <th scope="col">Sisa Kuota</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignedMentees as $data)
                        <tr>
                            <td>
                                <div class="media align-items-center">
                                    <div class="avatar-initial rounded-circle mr-3 bg-gradient-info text-white shadow-sm">
                                        {{ strtoupper(substr($data->mentee->name, 0, 1)) }}
                                    </div>
                                    <div class="media-body">
                                        <span class="mb-0 text-sm font-weight-bold d-block text-dark">{{ $data->mentee->name }}</span>
                                        <small class="text-muted">
                                            <i class="fas fa-graduation-cap text-info mr-1"></i> {{ $data->target_university ?? 'Belum Isi' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-outline-primary border text-primary">{{ $data->package->name }}</span>
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $data->remaining_quota }}</span> <small class="text-muted">Sesi</small>
                            </td>
                            <td>
                                @php $color = match($data->status) { 'active' => 'success', 'pending_approval' => 'warning', 'rejected' => 'danger', default => 'primary' }; @endphp
                                <span class="badge badge-dot mr-4">
                                    <i class="bg-{{ $color }}"></i>
                                    <span class="status font-weight-bold text-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $data->status)) }}</span>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v text-primary"></i>
                                    </a>
                                    
                                    {{-- FORM HIDDEN --}}
                                    <form id="approve-form-{{ $data->id }}" action="{{ route('mentor.mentees.approve', $data->id) }}" method="POST" style="display: none;">@csrf</form>
                                    <form id="reject-form-{{ $data->id }}" action="{{ route('mentor.mentees.reject', $data->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="reason_preset" id="reason-input-{{ $data->id }}">
                                    </form>

                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow shadow border-0">
                                        @if($data->status == 'pending_approval')
                                            <a class="dropdown-item text-success fw-bold" href="javascript:void(0)" onclick="handleAccept({{ $data->id }}, '{{ $data->mentee->name }}')">
                                                <i class="fas fa-check action-icon"></i> Terima Permintaan
                                            </a>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="handleReject({{ $data->id }}, '{{ $data->mentee->name }}')">
                                                <i class="fas fa-times action-icon"></i> Tolak Permintaan
                                            </a>
                                        @elseif($data->status == 'active')
                                            <a class="dropdown-item text-primary" href="#"><i class="fas fa-comment-dots action-icon"></i> Chat Mentee</a>
                                            <a class="dropdown-item" href="#"><i class="fas fa-history action-icon"></i> Riwayat Sesi</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <h4 class="text-muted font-weight-normal">Belum ada mentee terhubung</h4>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- PAKAI PUSH JS AGAR MASUK KE @STACK('JS') DI MASTER --}}
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Deklarasi Global agar terbaca onclick
    window.handleAccept = function(id, name) {
        Swal.fire({
            title: 'Terima Mentee?',
            text: "Anda akan membimbing " + name,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2dce89',
            cancelButtonColor: '#f5365c',
            confirmButtonText: 'Ya, Terima',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form-' + id).submit();
            }
        });
    }

    window.handleReject = function(id, name) {
        Swal.fire({
            title: 'Tolak Permintaan',
            text: "Pilih alasan penolakan untuk " + name,
            icon: 'warning',
            input: 'select',
            inputOptions: {
                'Jadwal Penuh': 'Jadwal bimbingan saya penuh',
                'Luar Keahlian': 'Topik di luar keahlian saya',
                'other': 'Lainnya (Ketik Manual)'
            },
            inputPlaceholder: '-- Pilih Alasan --',
            showCancelButton: true,
            confirmButtonColor: '#f5365c',
            confirmButtonText: 'Tolak',
            inputValidator: (value) => { if (!value) return 'Wajib pilih alasan!' }
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value === 'other') {
                    Swal.fire({
                        title: 'Alasan Spesifik',
                        input: 'textarea',
                        showCancelButton: true,
                        confirmButtonText: 'Kirim',
                        inputValidator: (v) => { if (!v) return 'Wajib diisi!' }
                    }).then((textRes) => { if(textRes.isConfirmed) executeReject(id, textRes.value); });
                } else {
                    executeReject(id, result.value);
                }
            }
        });
    }

    function executeReject(id, reason) {
        document.getElementById('reason-input-' + id).value = reason;
        document.getElementById('reject-form-' + id).submit();
    }

    // Toast Success
    @if(session('success'))
        Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: "{{ session('success') }}" });
    @endif
</script>
@endpush