<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ config('app.name', 'ScholarSeek') }} | Daftar Mentor
    </title>
    
    {{-- ASET ARGON --}}
    <link href="{{ asset('assets/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/argon-dashboard.css?v=1.1.2') }}" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-default">
    <div class="main-content">
        
        {{-- NAVBAR DIHAPUS --}}
        
        {{-- HEADER GRADIENT (DARI REGISTER TEMPLATE) --}}
        <div class="header bg-gradient-primary py-7 py-lg-8">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10">
                            <h1 class="text-white">Formulir Pendaftaran Mentor</h1>
                            <p class="text-lead text-light">Lengkapi data berikut untuk mengajukan diri sebagai mentor. Aplikasi Anda akan ditinjau oleh Admin.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>

        {{-- PAGE CONTENT (CARD FORMULIR) --}}
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card bg-secondary shadow border-0">
                        
                        {{-- ALERT VALIDASI GLOBAL --}}
                        <div class="p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">Harap perbaiki kesalahan pada formulir.</div>
                            @endif
                            @if (session('info'))
                                <div class="alert alert-info">{{ session('info') }}</div>
                            @endif
                        </div>
                        
                        <div class="card-body px-lg-5 py-lg-5">
                            <h6 class="heading-small text-muted mb-4">Informasi Kontak dan Kredensial</h6>
                            
                            {{-- FORM AKSI MENJADI STORE MENTOR --}}
                            <form method="POST" action="{{ route('mentor.register.store') }}" enctype="multipart/form-data">
                                @csrf 
                                
                                {{-- FIELD BIO (Narrative) --}}
                                <div class="form-group">
                                    <label for="bio">Bio & Riwayat Keahlian (Meyakinkan Admin)</label>
                                    <textarea name="bio" id="bio" rows="5" class="form-control @error('bio') is-invalid @enderror" placeholder="Jelaskan keahlian, riwayat pendidikan, dan motivasi menjadi mentor.">{{ old('bio', $existingMentor->bio ?? '') }}</textarea>
                                    @error('bio')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                                
                                {{-- FIELD EXPERTISE (CHECKBOXES) --}}
                                <div class="form-group">
                                    <label>Kredensial/Area Keahlian (Wajib Pilih)
                                        <a href="{{ route('admin.scholarship.categories.index') }}" target="_blank" class="text-info ml-2" title="Kelola Opsi Keahlian">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </label>
                                    <div class="row ml-1">
                                        @forelse ($expertiseOptions as $option)
                                            <div class="custom-control custom-checkbox col-md-4 mb-2">
                                                <input type="checkbox" class="custom-control-input" id="exp_{{ $option->id }}" name="expertise_areas[]" value="{{ $option->id }}" {{ is_array(old('expertise_areas')) && in_array($option->id, old('expertise_areas')) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="exp_{{ $option->id }}">{{ $option->name }}</label>
                                            </div>
                                        @empty
                                            <p class="text-danger">Harap buat kategori di Admin Panel terlebih dahulu.</p>
                                        @endforelse
                                    </div>
                                    @error('expertise_areas')
                                        <div class="text-danger mt-1">Anda wajib memilih minimal satu area keahlian.</div>
                                    @enderror
                                </div>
                                
                                {{-- KONTAK & DOMISILI --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="domicile_city">Kota Domisili</label>
                                            <input type="text" name="domicile_city" id="domicile_city" class="form-control @error('domicile_city') is-invalid @enderror" value="{{ old('domicile_city', $existingMentor->domicile_city ?? '') }}" required>
                                            @error('domicile_city')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone_number">Nomor Telepon</label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $existingMentor->phone_number ?? '') }}" required>
                                            @error('phone_number')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="full_address">Alamat Lengkap (Opsional)</label>
                                            <input type="text" name="full_address" id="full_address" class="form-control @error('full_address') is-invalid @enderror" value="{{ old('full_address', $existingMentor->full_address ?? '') }}">
                                            @error('full_address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <h6 class="heading-small text-muted mt-4 mb-4">Dokumen Pendukung (Max 10MB)</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cv_file">Unggah CV/Resume (Wajib)</label>
                                            <div class="custom-file">
                                                <input type="file" name="cv_file" id="cv_file" class="custom-file-input @error('cv_file') is-invalid @enderror" lang="in" required>
                                                <label class="custom-file-label" for="cv_file">Pilih file CV (.pdf, .doc, .docx)</label>
                                            </div>
                                            @error('cv_file')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="motivation_letter_file">Motivation Letter (Wajib)</label>
                                            <div class="custom-file">
                                                <input type="file" name="motivation_letter_file" id="motivation_letter_file" class="custom-file-input @error('motivation_letter_file') is-invalid @enderror" lang="in" required>
                                                <label class="custom-file-label" for="motivation_letter_file">Pilih file Motivation Letter</label>
                                            </div>
                                            @error('motivation_letter_file')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Submit Pendaftaran Mentor</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- HAPUS FOOTER ASLI ARGON --}}
    
    {{-- SCRIPTS ARGON --}}
    <script src="{{ asset('assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=1.1.2') }}"></script>
    
    <script>
        // Script untuk menampilkan nama file pada input custom-file
        document.querySelectorAll('.custom-file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });
    </script>
</body>
</html>