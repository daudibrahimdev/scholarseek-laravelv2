@extends('layouts.mentee_master')

@section('title', 'Home')

@section('content')
    <div class="container-fluid pb-5 hero-header bg-light mb-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <h1 class="display-1 mb-4 animated slideInRight">Every Dream <br>Is<span class="text-primary"> Worth</span>
                        Discovering</h1>
                    <h5 class="d-inline-block border border-2 border-white py-3 px-5 mb-0 animated slideInRight">
                        Find scholarships and opportunities that bring your dreams closer</h5>
                </div>
                <div class="col-lg-6">
                    <div class="owl-carousel header-carousel animated fadeIn">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-1.png') }}" alt="">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-2.png') }}" alt="">
                        <img class="img-fluid" src="{{ asset('mentee_assets/img/hero-slider-3.png') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="row g-5 animated fadeIn">
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 btn-square border border-2 border-white me-3">
                            <i class="fa fa-robot text-primary"></i>
                        </div>
                        <h5 class="lh-base mb-0">Temukan Beasiswa Impianmu</h5>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 btn-square border border-2 border-white me-3">
                            <i class="fa fa-robot text-primary"></i>
                        </div>
                        <h5 class="lh-base mb-0">Terhubung dengan Mentor Berpengalaman</h5>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 btn-square border border-2 border-white me-3">
                            <i class="fa fa-robot text-primary"></i>
                        </div>
                        <h5 class="lh-base mb-0">Bangun Rencana Belajarmu dari Sekarang</h5>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 btn-square border border-2 border-white me-3">
                            <i class="fa fa-robot text-primary"></i>
                        </div>
                        <h5 class="lh-base mb-0">Mulai Perjalanan Globalmu Hari Ini</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-5">
        <div class="container mt-5">
            <div class="row g-0">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex flex-column justify-content-center bg-primary h-100 p-5">
                        <h1 class="text-white mb-5">Berbagai <span
                                class="text-uppercase text-primary bg-light px-2">Program  </span></h1>
                        <h4 class="text-white mb-0"> <span class="display-1">Beasiswa</span></h4>
                    </div>
                    <a href="#" class="btn btn-light py-3 px-5 fw-bold rounded-pill">
                        Lihat Semua Beasiswa
                        <i class="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.2s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-1.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">Global Short Program</h4>
                                    <small class="text-white">72 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-2.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">Pascasarjana Global (Fully Funded)</h4>
                                    <small class="text-white">67 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.4s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-3.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">S1 Global: Fully Funded</h4>
                                    <small class="text-white">53 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-4.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">Beasiswa Nasional & Ikatan Dinas</h4>
                                    <small class="text-white">33 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.6s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-5.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">Beasiswa SMP & SMA</h4>
                                    <small class="text-white">87 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.7s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/project-6.png') }}" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-black">dan masih banyak lagi</h4>
                                    <small class="text-white">69 Projects</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="mb-5">Baca<span
                            class="text-uppercase text-primary bg-light px-2">Panduan Dokumen</span></h1>
                    <p>Langkah awal menuju keberhasilan aplikasi beasiswa adalah memastikan kelengkapan dan kepatuhan semua dokumen yang dipersyaratkan.</p>
                    <p class="mb-5">Karena setiap program beasiswa memiliki visi, misi, dan persyaratan uniknya, kami sediakan semua referensi dokumentasi esensial untuk kamu disini</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-0">
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.2s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-primary">
                                <a href="#!" class="service-img position-relative mb-4">
                                    <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/Service-1.png') }}" alt="">
                                    <h3>Panduan & Persyaratan Administrasi</h3>
                                </a>
                                <p class="mb-0">Kamu bisa cek berbagai dokumen panduan pendaftaran untuk berbagai beasiswa disini</p>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.4s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-light">
                                <a href="#!" class="service-img position-relative mb-4">
                                    <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/Service-2.png') }}" alt="">
                                    <h3>Kumpulan Esai</h3>
                                </a>
                                <p class="mb-0">Lihat koleksi esai terbaik dari para penerima beasiswa (awardee) yang berhasil lolos dari beragam program</p>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.6s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-light">
                                <a href="#!" class="service-img position-relative mb-4">
                                    <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/Service-3.png') }}" alt="">
                                    <h3>Kumpulan Motivation Letter</h3>
                                </a>
                                <p class="mb-0">Berbagai contoh-contoh Motivation Letter dari para awardee yang berhasil losos</p>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.8s">
                            <div class="service-item h-100 d-flex flex-column justify-content-center bg-primary">
                                <a href="#!" class="service-img position-relative mb-4">
                                    <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/service-44.png') }}" alt="">
                                    <h3>Contoh CV dan masih banyak lagi</h3>
                                </a>
                                <p class="mb-0">Akses beragam contoh Curriculum Vitae (CV) dan berbagai dokumentasi penting dan esensial terkait pendaftaran beasiswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-light py-3 px-5 fw-bold rounded-pill">
                Lihat Semua Dokumen
                <i class="fa fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <h1 class="mb-5">Konsultasikan dengan <span class="text-uppercase text-primary bg-light px-2">Para Mentor</span>
            </h1>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/team-1.jpg') }}" alt="">
                        <div class="team-overlay">
                            <small class="mb-2">Scholarship Advisor</small>
                            <h4 class="lh-base text-light">Boris Johnson</h4>
                            
                            <a href="#!" class="btn-apply">Ajukan Bimbingan</a>

                            <div class="d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!"><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center wow fadeIn" data-wow-delay="0.1s">
                <h1 class="mb-5">Our <span class="text-uppercase text-primary bg-light px-2">Pricing</span> Plan</h1>
            </div>
            <div class="table-responsive wow fadeIn" data-wow-delay="0.3s">
                <table class="table table-bordered table-striped text-center align-middle" style="min-width: 700px;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">Fitur</th>
                            <th scope="col">Group Masterclass</th>
                            <th scope="col">Private Class</th>
                            <th scope="col">Breakthrough Package</th>
                            <th scope="col">Ultimate Breakthrough Package<br>(cocok untuk 2-3 beasiswa)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="text-start">Harga</th>
                            <td>Rp. 70,000 per sesi</td>
                            <td>Rp. 300,000 per sesi</td>
                            <td>Rp. 2,500,000 / paket (3 bulan)</td>
                            <td>Rp. 4,500,000 / paket (6 bulan)</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-primary newsletter p-0">
        <div class="container p-0">
            <div class="row g-0 align-items-center">
                <div class="col-md-5 ps-lg-0 text-start wow fadeIn" data-wow-delay="0.2s">
                    <img class="img-fluid w-100" src="{{ asset('mentee_assets/img/newsletter.jpg') }}" alt="">
                </div>
                <div class="col-md-7 py-5 newsletter-text wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-5">
                        <h1 class="mb-5">Langganan untuk cari <span class="text-uppercase text-primary bg-white px-2">Mentor</span></h1>
                        <div class="position-relative w-100 mb-2">
                            <input class="form-control border-0 w-100 ps-4 pe-5" type="text" placeholder="Enter Your Email" style="height: 60px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-2 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                        <p class="mb-0">Diam sed sed dolor stet amet eirmod</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection