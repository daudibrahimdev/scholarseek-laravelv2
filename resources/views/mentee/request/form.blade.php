@extends('layouts.mentee_master')

@section('title', 'form Permintaan Sesi Baru')

@section('content')
<div class="container py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="text-primary text-uppercase"> Pengajuan Sesi </h6>
        <h1 class="mb-4">Permintaan Sesi untuk Paket: {{ $userPackage->package->name }}</h1>
        <p class="mb-4">Anda mengajukan sesi kepada Mentor: <strong>{{ $userPackage->mentor->user->name }}</strong></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.3s">
            {{-- Form akan POST ke route penyimpanan kita --}}
            <form action="{{ route('mentee.session.request.store') }}" method="POST">
                @csrf
                
                {{-- Input tersembunyi untuk data kunci --}}
                <input type="hidden" name="user_package_id" value="{{ $userPackage->id }}">
                <input type="hidden" name="mentor_id" value="{{ $userPackage->mentor_id }}">
                
                <div class="row g-3">
                    <div class="col-12">
                        <label for="topik" class="form-label">Topik Sesi yang Diminta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="topik" id="topik" placeholder="Contoh: Review CV dan Strategi Wawancara" required>
                    </div>
                    
                    <div class="col-12">
                        <label for="catatan" class="form-label">Catatan/Detail Kebutuhan Anda</label>
                        <textarea class="form-control" name="catatan" id="catatan" placeholder="Jelaskan secara singkat apa yang Anda harapkan dari sesi ini..." rows="6"></textarea>
                    </div>
                    
                    <div class="col-12">
                        {{-- Ingatkan Mentee bahwa jadwal belum final --}}
                        <div class="alert alert-info small mt-3">
                            Permintaan ini akan dicatat dengan status **PENDING**. Jadwal sesi akan ditentukan dan dikonfirmasi langsung oleh Mentor.
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button class="btn btn-primary py-3 px-5" type="submit">Kirim Permintaan Sesi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection