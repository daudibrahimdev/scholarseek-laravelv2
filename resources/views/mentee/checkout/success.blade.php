@extends('layouts.mentee_master')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                {{-- Icon Jam Melingkar --}}
                <div class="mb-4">
                    <i class="bi bi-clock-history text-warning display-1"></i>
                </div>
                
                <h2 class="mb-3 fw-bold">Selesaikan Pembayaran</h2>
                <p class="text-muted">Silakan transfer sesuai nominal berikut melalui <strong>{{ $transaction->payment_method }}</strong>:</p>
                
                {{-- Nominal Box ScholarSeek --}}
                <div class="bg-light p-4 rounded-3 mb-4 border">
                    <h1 class="text-primary mb-0 fw-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h1>
                    <small class="text-muted text-uppercase fw-bold">ID Transaksi: {{ $transaction->reference_id ?? $transaction->id }}</small>
                </div>

                {{-- Instruksi Box --}}
                <div class="alert alert-info border-0 small text-start p-4 mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Instruksi Pembayaran ScholarSeek:</h6>
                    <ol class="mb-0 ps-3 text-dark">
                        <li>Transfer ke rekening fiktif <strong>ScholarSeek</strong>: 1234567890</li>
                        <li>Pastikan nominal transfer sesuai hingga digit terakhir.</li>
                        <li>Simpan bukti transfer sebagai referensi.</li>
                        <li>Klik tombol <strong>"Konfirmasi Bayar"</strong> di bawah ini.</li>
                    </ol>
                </div>

                {{-- Tombol Konfirmasi --}}
                <form action="{{ route('mentee.checkout.confirm', $transaction->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Bayar
                    </button>
                </form>
                
                {{-- Tombol Cancel dengan SweetAlert --}}
                <form action="{{ route('mentee.checkout.cancel', $transaction->id) }}" method="POST" id="cancelForm" class="mt-3">
                    @csrf
                    <button type="button" onclick="handleCancel()" class="btn btn-link text-danger text-decoration-none small fw-bold">
                        <i class="bi bi-trash3 me-1"></i> Batalkan & Ganti Paket
                    </button>
                </form>
            </div>
            
            <p class="mt-4 text-muted small">&copy; 2026 ScholarSeek Indonesia. All rights reserved.</p>
        </div>
    </div>
</div>

{{-- Tambahkan library SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function handleCancel() {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            text: "Anda akan diarahkan kembali ke halaman daftar paket untuk memilih ulang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6b68', // Warna ScholarSeek
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form cancel jika mentee yakin
                document.getElementById('cancelForm').submit();
            }
        })
    }

    // Tampilkan pesan sukses/error dari session jika ada
    @if(session('success'))
        Swal.fire('Berhasil!', "{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        Swal.fire('Gagal!', "{{ session('error') }}", 'error');
    @endif
</script>

<style>
    .btn-primary {
        background-color: #0d6b68; /* Warna brand ScholarSeek */
        border-color: #0d6b68;
    }
    .btn-primary:hover {
        background-color: #0a5451;
        border-color: #0a5451;
    }
    .text-primary {
        color: #0d6b68 !important;
    }
</style>
@endsection