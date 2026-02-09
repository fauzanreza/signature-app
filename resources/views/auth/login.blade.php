@extends('layouts.app')

@section('content')
<div class="modern-login-container">
    <!-- Left Section: Visual & Branding -->
    <div class="login-visual-section">
        <div class="visual-overlay"></div>
        <div class="visual-content d-flex flex-column align-items-center justify-content-center h-100 text-center p-5">
            <div class="branding-hero mb-5">
                <div class="logo-wrapper mb-4 d-flex justify-content-center">
                    <img src="{{ asset('images/logo-qr.png') }}" alt="Logo" class="img-fluid" style="height: 60px; filter: brightness(0) invert(1);">
                </div>
                <h1 class="text-white fw-bold display-4 mb-3">Digital Signature</h1>
                <p class="text-white-50 lead mx-auto" style="max-width: 450px;">The most secure and efficient way to manage and sign your professional documents digitally.</p>
            </div>
            
            <div class="social-connect mt-4">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div style="height: 1px; width: 50px; background: rgba(255,255,255,0.2);"></div>
                    <span class="text-white mx-3 small text-uppercase fw-bold" style="letter-spacing: 3px;">Connect With Us</span>
                    <div style="height: 1px; width: 50px; background: rgba(255,255,255,0.2);"></div>
                </div>
                <div class="social-media-list d-flex align-items-center justify-content-center">
                    <a href="#" class="text-white mx-3 hover-scale"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-white mx-3 hover-scale"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white mx-3 hover-scale"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white mx-3 hover-scale"><i class="fab fa-linkedin-in fa-lg"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section: Login Form -->
    <div class="login-form-section">
        <div class="login-card shadow-2xl">
            <div class="card-body p-5">
                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-2">Welcome Back!</h2>
                    <p class="text-muted">Log in to start managing your documents with ease.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="login" class="small fw-bold text-dark">Email or Username</label>
                        <input id="login" type="text" class="form-control modern-input @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="username" autofocus placeholder="Input your email or username">
                        @error('login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="small fw-bold text-dark">Password</label>
                        <div class="position-relative">
                            <input id="password" type="password" class="form-control modern-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Input your password">
                            <span class="password-toggle">
                                <i class="far fa-eye text-muted"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="remember">Remember Me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="small fw-bold text-primary" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-modern py-3 fw-bold mb-4">
                        Login
                    </button>


                </form>

                <!-- <div class="text-center mt-5">
                    <p class="text-muted small">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary fw-bold ms-1">Sign up here</a>
                    </p>
                </div> -->
            </div>
        </div>
    </div>
</div>

<style>
    /* Layout Overrides */
    body, html {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }
    #app, main {
        height: 100%;
        padding: 0 !important;
    }
    .container {
        max-width: none !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .modern-login-container {
        display: flex;
        height: 100vh;
        width: 100vw;
    }

    /* Left Section Styles */
    .login-visual-section {
        flex: 1;
        position: relative;
        background: #1a202c; /* Fallback */
        background-image: url('{{ asset('images/login-bg.webp') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        padding: 60px;
        color: white;
    }
    .visual-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(158, 21, 32, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);
        z-index: 1;
    }
    .visual-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .visual-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .logo-wrapper {
        display: flex;
        align-items: center;
    }
    .hover-opacity-100:hover {
        opacity: 1 !important;
        text-decoration: none;
    }
    .hover-scale {
        transition: transform 0.2s;
        opacity: 0.8;
    }
    .hover-scale:hover {
        transform: scale(1.2);
        opacity: 1;
        color: white;
    }
    .dot {
        height: 8px;
        width: 8px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .dot.active {
        width: 24px;
        border-radius: 4px;
        background-color: white;
    }

    /* Right Section Styles */
    .login-form-section {
        flex: 0 0 45%;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
    }
    .login-card {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 24px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }

    /* Form Elements */
    .modern-input {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        height: auto;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .modern-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(158, 21, 32, 0.1);
    }
    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }
    .btn-modern {
        border-radius: 12px;
        transition: all 0.3s;
    }
    .btn-primary.btn-modern {
        background-color: #1a202c; /* Dark like in the image */
        border: none;
    }
    .btn-primary.btn-modern:hover {
        background-color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    }
    .or-separator::before, .or-separator::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 80px;
        height: 1px;
        background-color: #e2e8f0;
    }
    .or-separator::before { right: 100%; }
    .or-separator::after { left: 100%; }

    @media (max-width: 1200px) {
        .login-form-section {
            flex: 0 0 50%;
        }
    }

    @media (max-width: 992px) {
        .modern-login-container {
            flex-direction: column;
            overflow-y: auto;
        }
        .login-visual-section {
            flex: none;
            min-height: 40vh;
            padding: 40px 20px;
        }
        .login-form-section {
            flex: none;
            padding: 20px;
            background: transparent;
        }
        .login-card {
            margin-top: -40px;
            z-index: 10;
            margin-bottom: 40px;
        }
        body, html {
            overflow: auto;
        }
    }

    .password-toggle {
        transition: all 0.2s;
        padding: 5px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .password-toggle:hover {
        background-color: rgba(0,0,0,0.05);
    }
    .password-toggle i {
        transition: color 0.2s;
    }
    .password-toggle:hover i {
        color: var(--primary-color) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordToggle = document.querySelector('.password-toggle');
    const passwordInput = document.querySelector('#password');
    
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
});
</script>
@endsection
