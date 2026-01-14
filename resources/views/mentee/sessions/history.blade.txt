@extends('layouts.mentee_master')

@section('content')
<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase">// History //</h6>
        <h1 class="mb-5">Riwayat Kelas Selesai</h1>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Judul Sesi</th>
                            <th>Mentor</th>
                            <th>Tanggal & Waktu</th>
                            <th>Tipe</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $session)
                            <tr>
                                <td>
                                    <strong>{{ $session->title }}</strong>
                                    <p class="small text-muted mb-0">{{ Str::limit($session->description, 50) }}</p>
                                </td>
                                <td>{{ $session->mentor->user->name }}</td>
                                <td>
                                    {{ $session->start_time->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $session->type == '1on1' ? 'info' : 'success' }}">
                                        {{ $session->type }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('mentee.sessions.hide', $session->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini dari daftar?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Belum ada riwayat kelas yang selesai.
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