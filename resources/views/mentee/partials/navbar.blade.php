<div class="container-fluid sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">
            <a href="{{ route('home') }}" class="navbar-brand">
                <h1>iSTUDIO</h1>
            </a>
            <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="{{ route('home') }}" class="nav-item nav-link active">Home</a>
                    <a href="#" class="nav-item nav-link">Consultation</a>
                    <a href="#" class="nav-item nav-link">Scholarships</a>
                    <a href="#" class="nav-item nav-link">Student Guide</a>
                    
                    @auth
                        <a href="{{ route('home') }}" class="nav-item nav-link">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <a href="#" class="nav-item nav-link" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>