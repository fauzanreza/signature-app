@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 24px;">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center mb-5">
                            <div class="mb-4">
                                <img src="{{ asset('images/logo-qr.png') }}" alt="Logo" style="height: 60px; width: auto;">
                            </div>
                            <h2 class="font-weight-bold text-dark mb-1">Create Account</h2>
                            <p class="text-muted">Join Digital Signature to start signing documents</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="name" class="small font-weight-bold text-muted text-uppercase mb-2">Full Name</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                    </div>
                                    <input id="name" type="text" class="form-control form-control-lg border-left-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe" style="font-size: 0.95rem; background-color: #f9fafb;">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="email" class="small font-weight-bold text-muted text-uppercase mb-2">Email Address</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                    </div>
                                    <input id="email" type="email" class="form-control form-control-lg border-left-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@company.com" style="font-size: 0.95rem; background-color: #f9fafb;">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="password" class="small font-weight-bold text-muted text-uppercase mb-2">Password</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0">
                                                    <i class="fas fa-lock text-muted"></i>
                                                </span>
                                            </div>
                                            <input id="password" type="password" class="form-control form-control-lg border-left-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••" style="font-size: 0.95rem; background-color: #f9fafb;">
                                            <div class="input-group-append">
                                                <span class="password-toggle" data-target="#password">
                                                    <i class="far fa-eye text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="password-confirm" class="small font-weight-bold text-muted text-uppercase mb-2">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0">
                                                    <i class="fas fa-check-circle text-muted"></i>
                                                </span>
                                            </div>
                                            <input id="password-confirm" type="password" class="form-control form-control-lg border-left-0" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" style="font-size: 0.95rem; background-color: #f9fafb;">
                                            <div class="input-group-append">
                                                <span class="password-toggle" data-target="#password-confirm">
                                                    <i class="far fa-eye text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="custom-control-label small text-muted font-weight-medium" for="terms">
                                        I agree to the <a href="#" class="text-primary font-weight-bold">Terms of Service</a> and <a href="#" class="text-primary font-weight-bold">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold py-3 shadow-sm transition-all" style="border-radius: 12px;">
                                Create Account
                            </button>
                        </form>
                        
                        <div class="text-center mt-5">
                            <p class="text-muted small mb-0">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-primary font-weight-bold ml-1">Sign in instead</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <p class="text-muted small">&copy; {{ date('Y') }} Digital Signature. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(158, 21, 32, 0.2);
    }
    .input-group-merge .form-control:focus + .input-group-prepend .input-group-text {
        border-color: var(--primary-color);
    }
    .form-control:focus {
        background-color: #fff !important;
    }

    .password-toggle {
        cursor: pointer;
        transition: all 0.2s;
        padding: 0 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9fafb;
        border: 1px solid #ced4da;
        border-left: 0;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    .password-toggle:hover i {
        color: var(--primary-color) !important;
    }
    .form-control-lg {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.password-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const inputId = this.getAttribute('data-target');
            const input = document.querySelector(inputId);
            
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    });
});
</script>
@endsection
