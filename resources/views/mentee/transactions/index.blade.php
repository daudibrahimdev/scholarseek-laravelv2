@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase font-weight-bold">// Finance //</h6>
        <h1 class="mb-5">Riwayat Transaksi</h1>
    </div>

    <div class="card shadow border-0 p-4 wow fadeInUp" data-wow-delay="0.3s" style="border-radius: 15px;">
        <div class="table-responsive">
            <table class="table align-items-center table-flush mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-primary fw-bold" style="width: 15%">ID Transaksi</th>
                        <th class="text-primary fw-bold">Nama Paket</th>
                        <th class="text-primary fw-bold">Metode</th>
                        <th class="text-primary fw-bold">Total Bayar</th>
                        <th class="text-primary fw-bold text-center">Status</th>
                        <th class="text-primary fw-bold">Tanggal</th>
                        <th class="text-primary fw-bold text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    @forelse($transactions as $t)
                    <tr>
                        <td>
                            <span class="font-weight-bold">
                                #{{ strtoupper(substr($t->reference_id ?? $t->id, 0, 8)) }}
                            </span>
                        </td>
                        <td>{{ $t->package->name ?? 'Paket Bimbingan' }}</td>
                        <td class="text-uppercase small fw-bold text-muted">
                            {{ $t->payment_method ?? 'Transfer' }}
                        </td>
                        <td>
                            <span class="font-weight-bold">
                                Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($t->status == 'paid' || $t->status == 'success')
                                <span class="badge bg-success text-white px-3 py-2">Berhasil</span>
                            @elseif($t->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2">Menunggu</span>
                            @elseif($t->status == 'cancelled' || $t->status == 'dibatalkan')
                                <span class="badge bg-secondary text-white px-3 py-2">Dibatalkan</span>
                            @else
                                <span class="badge bg-danger text-white px-3 py-2">Gagal</span>
                            @endif
                        </td>
                        <td class="small">{{ $t->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                @if($t->status == 'pending')
                                    {{-- Tombol Bayar Sekarang (Lebih Kecil & Presisi) --}}
                                    <a href="{{ route('mentee.checkout.success', $t->id) }}" 
                                       class="btn btn-xs btn-warning fw-bold px-2 py-1 shadow-sm" 
                                       style="font-size: 11px; white-space: nowrap;">
                                        <i class="bi bi-wallet2 me-1"></i> Bayar
                                    </a>

                                    {{-- Tombol Cancel --}}
                                    <form action="{{ route('mentee.checkout.cancel', $t->id) }}" 
                                          method="POST" id="cancelForm{{ $t->id }}" class="m-0 p-0">
                                        @csrf
                                        <button type="button" 
                                                onclick="confirmCancel('{{ $t->id }}')" 
                                                class="btn btn-sm btn-outline-danger border-0 p-1" 
                                                title="Hapus Transaksi">
                                            <i class="bi bi-trash3-fill" style="font-size: 14px;"></i>
                                        </button>
                                    </form>
                                @elseif($t->status == 'paid' || $t->status == 'success')
                                    <a href="{{ route('mentee.transactions.invoice', $t->id) }}" 
                                       class="btn btn-sm btn-outline-primary px-3 fw-bold shadow-sm">
                                        <i class="bi bi-receipt me-1"></i> Invoice
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-cart-x text-muted" style="font-size: 3.5rem;"></i>
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
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#0d6b68' });
    @endif
</script>

<style>
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border-top: none;
        vertical-align: middle;
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 5px;
    }
    .btn-warning { background-color: #ffc107; border-color: #ffc107; color: #000; }
    .btn-warning:hover { background-color: #e0a800; color: #000; }
    .badge { font-weight: 700; border-radius: 6px; font-size: 10px; }
</style>
@endsection