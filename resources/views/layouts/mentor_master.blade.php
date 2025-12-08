<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ScholarSeek | @yield('title', 'Mentor Dashboard')</title>
    
    {{-- ASET ARGON --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('assets/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/argon-dashboard.css?v=1.1.2') }}" rel="stylesheet" />
    @stack('css')
</head>

<body class="">
    
    {{-- SLOT SIDEBAR MENTOR --}}
    @yield('sidebar') 
    
    <div class="main-content">
        
        {{-- NAVBAR ADMIN/MENTOR --}}
        @include('mentor.partials.navbar') {{-- Kita asumsikan Navbar sama persis --}}
        
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    @yield('header_stats')
                </div>
            </div>
        </div>
        
        <div class="container-fluid mt--7">
            @yield('content')
            
            {{-- FOOTER --}}
            @include('admin.partials.footer')
        </div>
    </div>
    
    {{-- SCRIPTS ARGON --}}
    <script src="{{ asset('assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=1.1.2') }}"></script>
    @stack('js')
</body>

</html>