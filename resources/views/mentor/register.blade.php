@extends('admin.layouts.app') // Gunakan layout dasar Argon

@section('title', 'Daftar Sebagai Mentor')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Formulir Pendaftaran Mentor</h3>
                </div>
                
                {{-- ALERT STATUS --}}
                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif
                
                <div class="card-body">
                    {{-- Form harus punya enctype untuk file upload --}}
                    <form method="POST" action="{{ route('mentor.register.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Field Bio --}}
                        <div class="form-group">
                            <label for="bio">Bio & Riwayat Keahlian (Meyakinkan Admin)</label>
                            <textarea name="bio" id="bio" rows="5" class="form-control @error('bio') is-invalid @enderror" placeholder="Jelaskan keahlian Anda, riwayat pendidikan, dan motivasi menjadi mentor.">{{ old('bio', $existingMentor->bio ?? '') }}</textarea>
                            @error('bio')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Field Expertise Areas (Checkboxes) --}}
                        <div class="form-group">
                            <label>Kredensial/Area Keahlian (Wajib Pilih)</label>
                            <div class="row ml-1">
                                @forelse ($expertiseOptions as $option)
                                    <div class="custom-control custom-checkbox col-md-4 mb-2">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="exp_{{ $option->id }}" 
                                               name="expertise_areas[]" 
                                               value="{{ $option->id }}"
                                               {{ is_array(old('expertise_areas')) && in_array($option->id, old('expertise_areas')) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="exp_{{ $option->id }}">{{ $option->name }}</label>
                                    </div>
                                @empty
                                    <p class="text-danger">Harap buat kategori keahlian terlebih dahulu di Admin Panel.</p>
                                @endforelse
                            </div>
                            @error('expertise_areas')
                                <div class="text-danger mt-1">Anda wajib memilih minimal satu area keahlian.</div>
                            @enderror
                        </div>

                        {{-- Group Alamat & Kontak --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="domicile_city">Kota Domisili</label>
                                    <input type="text" name="domicile_city" id="domicile_city" class="form-control @error('domicile_city') is-invalid @enderror" value="{{ old('domicile_city', $existingMentor->domicile_city ?? '') }}">
                                    @error('domicile_city')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_number">Nomor Telepon</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $existingMentor->phone_number ?? '') }}">
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

                        {{-- Group File Upload --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cv_file">Unggah CV/Resume (Wajib)</label>
                                    <div class="custom-file">
                                        <input type="file" name="cv_file" id="cv_file" class="custom-file-input @error('cv_file') is-invalid @enderror" lang="in">
                                        <label class="custom-file-label" for="cv_file">Pilih file CV (.pdf, .doc, .docx)</label>
                                    </div>
                                    @error('cv_file')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="motivation_letter_file">Motivation Letter (Wajib)</label>
                                    <div class="custom-file">
                                        <input type="file" name="motivation_letter_file" id="motivation_letter_file" class="custom-file-input @error('motivation_letter_file') is-invalid @enderror" lang="in">
                                        <label class="custom-file-label" for="motivation_letter_file">Pilih file Motivation Letter</label>
                                    </div>
                                    @error('motivation_letter_file')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Submit Pendaftaran Mentor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('js')
        <script>
            // Script sederhana untuk menampilkan nama file pada input custom-file (Bootstrap/Argon feature)
            document.querySelectorAll('.custom-file-input').forEach(input => {
                input.addEventListener('change', function(e) {
                    var fileName = e.target.files[0].name;
                    var nextSibling = e.target.nextElementSibling;
                    nextSibling.innerText = fileName;
                });
            });
        </script>
    @endpush
@endsection