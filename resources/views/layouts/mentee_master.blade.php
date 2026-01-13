<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Scholar Seek | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="{{ asset('mentee_assets/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('mentee_assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mentee_assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <link href="{{ asset('mentee_assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('mentee_assets/css/style.css') }}" rel="stylesheet">
    
    @stack('css')
</head>

<body>
    @php
    // Ini logic buat cek kalo mentee punya paket yang statusnya 'pending_assignment' 
    $needsMentorAction = false;
    if (auth()->check()) {
        $needsMentorAction = \App\Models\UserPackage::where('user_id', auth()->id())
            ->where('status', 'pending_assignment')
            ->exists();
    }
@endphp
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @include('mentee.partials.navbar')

    @yield('content')

    @include('mentee.partials.footer')

    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('mentee_assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('mentee_assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('mentee_assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('mentee_assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('mentee_assets/js/main.js') }}"></script>
    
    @stack('js')
</body>

</html>