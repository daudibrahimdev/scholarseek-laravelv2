@extends('layouts.mentee_master')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid pb-5 bg-primary hero-header">
    <div class="container py-5">
        <div class="row g-3 justify-content-center">
            <div class="col-12 text-center">
                <h1 class="display-1 mb-0 animated zoomIn text-black">Riwayat Transaksi</h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden wow fadeInUp" data-wow-delay="0.3s">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-primary">ID & Paket</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Metode</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Total Bayar</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary text-center">Status</th>
                            <th class="py-3 text-uppercase small fw-bold text-primary">Tanggal</th>
                            <th class="pe-4 py-3 text-center text-uppercase small fw-bold text-primary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="fw-bold text-dark">#{{ strtoupper(substr($t->reference_id ?? $t->id, 0, 8)) }}</div>
                                <div class="small text-muted">{{ $t->package->name ?? 'Paket Bimbingan' }}</div>
                            </td>
                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="btn-sm-square bg-light rounded-circle me-2">
                                        <i class="bi bi-credit-card-2-front text-primary"></i>
                                    </div>
                                    <span class="text-uppercase small fw-bold text-muted">{{ $t->payment_method ?? 'Transfer' }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="fw-bold text-dark">Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center py-4">
                                @if($t->status == 'paid' || $t->status == 'success')
                                    <span class="badge bg-soft-success text-success border border-success rounded-pill px-3 py-2">Berhasil</span>
                                @elseif($t->status == 'pending')
                                    <span class="badge bg-soft-warning text-warning border border-warning rounded-pill px-3 py-2">Menunggu</span>
                                @elseif($t->status == 'cancelled' || $t->status == 'dibatalkan')
                                    <span class="badge bg-soft-secondary text-secondary border border-secondary rounded-pill px-3 py-2">Batal</span>
                                @else
                                    <span class="badge bg-soft-danger text-danger border border-danger rounded-pill px-3 py-2">Gagal</span>
                                @endif
                            </td>
                            <td class="py-4 small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $t->created_at->format('d M Y') }}
                            </td>
                            <td class="pe-4 text-center py-4">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    @if($t->status == 'pending')
                                        <a href="{{ route('mentee.checkout.success', $t->id) }}" 
                                           class="btn btn-warning btn-sm fw-bold rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-wallet2 me-1"></i> Bayar
                                        </a>

                                        <form action="{{ route('mentee.checkout.cancel', $t->id) }}" 
                                              method="POST" id="cancelForm{{ $t->id }}" class="m-0 p-0">
                                            @csrf
                                            <button type="button" onclick="confirmCancel('{{ $t->id }}')" 
                                                    class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @elseif($t->status == 'paid' || $t->status == 'success')
                                        <a href="{{ route('mentee.transactions.invoice', $t->id) }}" 
                                           class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="bi bi-receipt me-1"></i> Invoice
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted">Belum ada riwayat transaksi.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmCancel(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Transaksi tertunda ini akan dihapus dari riwayat.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#0d6b68',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancelForm' + id).submit();
            }
        })
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#0d6b68', borderRadius: '15px' });
    @endif
</script>

<style>
    /* Desain Soft Badge Elegan */
    .bg-soft-success { background-color: #ecfdf5; }
    .bg-soft-warning { background-color: #fffbeb; }
    .bg-soft-danger { background-color: #fef2f2; }
    .bg-soft-secondary { background-color: #f8fafc; }
    
    .badge { font-size: 11px; font-weight: 700; }

    /* Hover Effect Row */
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
        transition: 0.2s;
    }

    /* Icon Square Style */
    .btn-sm-square {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection