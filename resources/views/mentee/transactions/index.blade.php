@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase font-weight-bold">// Finance //</h6>
        <h1 class="mb-5">Riwayat Transaksi</h1>
    </div>

    <div class="card shadow border-0 p-4 wow fadeInUp" data-wow-delay="0.3s">
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th class="text-primary">ID Transaksi</th>
                        <th class="text-primary">Nama Paket</th>
                        <th class="text-primary">Metode</th>
                        <th class="text-primary">Total Bayar</th>
                        <th class="text-primary">Status</th>
                        <th class="text-primary">Tanggal</th>
                        <th class="text-primary text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td><span class="font-weight-bold">#{{ strtoupper(substr($t->reference_id ?? $t->id, 0, 8)) }}</span></td>
                        <td>{{ $t->package->name ?? 'Paket Bimbingan' }}</td>
                        <td class="text-uppercase small">{{ $t->payment_method ?? 'Transfer' }}</td>
                        <td><span class="text-dark font-weight-bold">Rp {{ number_format($t->amount, 0, ',', '.') }}</span></td>
                        <td>
                            @if($t->status == 'paid' || $t->status == 'success')
                                <span class="badge bg-success text-white px-3">Berhasil</span>
                            @elseif($t->status == 'pending')
                                <span class="badge bg-warning text-dark px-3">Menunggu</span>
                            @else
                                <span class="badge bg-danger text-white px-3">Gagal</span>
                            @endif
                        </td>
                        <td>{{ $t->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('mentee.transactions.invoice', $t->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="bi bi-receipt me-1"></i> Lihat Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">Belum ada riwayat transaksi yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection