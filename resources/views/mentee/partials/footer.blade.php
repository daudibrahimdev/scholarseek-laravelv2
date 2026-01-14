<div class="container-fluid bg-dark text-white-50 footer pt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                <a href="{{ route('home') }}" class="d-inline-block mb-3">
                    {{-- Menggunakan Logo PNG ScholarSeek --}}
                    <img src="{{ asset('assets/img/logo-scholarseek.png') }}" alt="ScholarSeek" style="height: 50px; filter: brightness(0) invert(1);">
                </a>
                <p class="mb-0">ScholarSeek adalah platform terpercaya yang membantu kamu menemukan beasiswa impian dan terhubung dengan mentor profesional untuk membimbing perjalanan akademikmu ke level global.</p>
            </div>
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                <h5 class="text-white mb-4">Hubungi Kami</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i>Jakarta, Indonesia</p>
                <p><i class="fa fa-envelope me-3"></i>scholarseeksupport@gmail.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-primary btn-square border-2 me-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-primary btn-square border-2 me-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-primary btn-square border-2 me-2" href="#!"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                <h5 class="text-white mb-4">Akses Cepat</h5>
                <a class="btn btn-link" href="{{ route('mentee.scholarships.index') }}">Beasiswa</a>
                <a class="btn btn-link" href="{{ route('mentee.mentors.index') }}">Cari Mentor</a>
                <a class="btn btn-link" href="{{ route('mentee.student_guide.index') }}">Student Guide</a>
            </div>
        </div>
    </div>
    <div class="container wow fadeIn" data-wow-delay="0.1s">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ date('Y') }} <a class="border-bottom" href="#">ScholarSeek</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
</div>