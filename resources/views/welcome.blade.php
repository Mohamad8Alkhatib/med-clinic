<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .btn-custom {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            margin: 10px;
        }
        .card-feature {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        .card-feature:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="background: transparent;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-hospital"></i> Medical Clinic
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{-- <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div> --}}
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Book Your Medical Appointment Online</h1>
                    <p class="lead mb-4">Easy, fast, and convenient way to schedule appointments with our professional doctors.</p>
                    <div>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-custom btn-lg">
                                <i class="bi bi-person-plus"></i> Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-custom btn-lg">
                                <i class="bi bi-speedometer2"></i> Go to Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-calendar-check" style="font-size: 15rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose Us?</h2>
                <p class="text-muted">Simple and efficient appointment management system</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-feature p-4 text-center">
                        <i class="bi bi-clock-history feature-icon"></i>
                        <h4>24/7 Booking</h4>
                        <p class="text-muted">Book appointments anytime, anywhere at your convenience.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature p-4 text-center">
                        <i class="bi bi-people feature-icon"></i>
                        <h4>Expert Doctors</h4>
                        <p class="text-muted">Access to qualified and experienced medical professionals.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature p-4 text-center">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4>Secure & Private</h4>
                        <p class="text-muted">Your health information is safe and confidential.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">How It Works</h2>
                <p class="text-muted">Simple steps to book your appointment</p>
            </div>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="mb-3">
                        <span class="badge bg-primary rounded-circle p-3 fs-4">1</span>
                    </div>
                    <h5>Register</h5>
                    <p class="text-muted">Create your account</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <span class="badge bg-primary rounded-circle p-3 fs-4">2</span>
                    </div>
                    <h5>Choose Doctor</h5>
                    <p class="text-muted">Select from available specialists</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <span class="badge bg-primary rounded-circle p-3 fs-4">3</span>
                    </div>
                    <h5>Pick Time</h5>
                    <p class="text-muted">Choose convenient time slot</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <span class="badge bg-primary rounded-circle p-3 fs-4">4</span>
                    </div>
                    <h5>Get Confirmed</h5>
                    <p class="text-muted">Receive appointment confirmation</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Medical Clinic. All rights reserved.</p>
            <small class="text-muted">Made with <i class="bi bi-heart-fill text-danger"></i> for better healthcare</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>