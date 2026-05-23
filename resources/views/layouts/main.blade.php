<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Medical Clinic') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --main-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100% ); }
        body { background-color: #f8f9fa; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background: var(--main-gradient) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .footer { background: #212529; color: white; margin-top: auto; }
        .btn-primary { background: var(--main-gradient); border: none; }
        .btn-primary:hover { opacity: 0.9; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-hospital"></i> Medical Clinic
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        {{-- Common link for all logged-in users --}}
                        <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Home</a></li>

                        {{-- Links for Patient --}}
                        @if(auth()->user()->role == 'patient')
                            <li class="nav-item"><a class="nav-link" href="{{ url('/patient/doctors') }}">Doctors</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/patient/my-appointments') }}">My Appointments</a></li>
                        @endif

                        {{-- Links for Admin --}}
                        @if(auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-warning" href="{{ url('/admin/dashboard') }}">
                                    <i class="bi bi-shield-lock"></i> Admin Panel
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-light text-dark px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-light text-dark ms-lg-2 px-3 rounded-pill" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success and Error Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="container mb-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Medical Clinic. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
