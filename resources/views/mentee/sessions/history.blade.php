@extends('layouts.mentee_master')

@section('title', 'Riwayat Kelas')

@section('content')
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Riwayat Kelas</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0"> {{-- P-0 agar header tabel menempel ke pinggir card --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-primary">Judul Sesi</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Mentor</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Tanggal & Waktu</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Tipe</th>
                            <th class="pe-4 py-3 text-center text-uppercase small fw-bold text-primary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $session)
                            <tr>
                                <td class="ps-4 py-4">
                                    <div class="fw-bold text-dark">{{ $session->title }}</div>
                                    <div class="small text-muted">{{ Str::limit($session->description, 60) }}</div>
                                </td>
                                <td class="py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 btn-sm-square bg-primary rounded-circle me-2">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <span>{{ $session->mentor->user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="text-dark fw-medium">{{ $session->start_time->format('d M Y') }}</div>
                                    <div class="small text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                    </div>
                                </td>
                                <td class="py-4">
                                    @if($session->type == '1on1')
                                        <span class="badge bg-soft-primary text-primary border border-primary rounded-pill px-3">Private</span>
                                    @else
                                        <span class="badge bg-soft-success text-success border border-success rounded-pill px-3">Group</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-center py-4">
                                    <form action="{{ route('mentee.sessions.hide', $session->id) }}" method="POST" onsubmit="return confirm('Sembunyikan riwayat ini dari daftar?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash3 me-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada riwayat kelas yang selesai.</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Desain Badge Elegant */
    .bg-soft-primary { background-color: #eef2ff; }
    .bg-soft-success { background-color: #ecfdf5; }
    
    /* Hover Effect Row */
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
        transition: 0.2s;
    }

    /* Icon Square Style */
    .btn-sm-square {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection