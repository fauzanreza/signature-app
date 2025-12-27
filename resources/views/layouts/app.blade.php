<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #9e1520;
            --primary-dark: #7d1119;
            --primary-light: #d6303c;
            --accent-bg: #fdf2f3;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg-body: #f3f4f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }
        
        /* Navbar */
        .navbar {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            background-color: #fff !important;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--primary-color) !important;
            font-size: 1.25rem;
        }
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            background: #fff;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: var(--text-dark);
            padding: 1.25rem 1.5rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
            letter-spacing: 0.025em;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: 0 4px 6px -1px rgba(158, 21, 32, 0.4);
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        /* Forms */
        .form-control {
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 0.6rem 1rem;
            color: var(--text-dark);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(158, 21, 32, 0.1);
        }
        .input-group-text {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: var(--text-muted);
        }
        
        /* Utilities */
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        
        /* Badges */
        .badge-success { background-color: #059669; }
        .badge-warning { background-color: #d97706; color: white; }
        .badge-secondary { background-color: #6b7280; }
        
        /* Auth Page Specifics */
        .auth-layout {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239e1520' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        /* Responsive Width Utilities */
        @media (min-width: 768px) {
            .w-md-auto { width: auto !important; }
        }
        @media (min-width: 992px) {
            .w-lg-auto { width: auto !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        @if(!in_array(Route::currentRouteName(), ['login', 'register']))
        <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ Auth::check() ? route('dashboard') : url('/') }}">
                    <img src="{{ asset('images/logo-qr.png') }}" alt="Logo" class="mr-2" style="height: 40px; width: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; color: var(--primary-color);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        {{ Auth::user()->name }}
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item py-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2 text-muted"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        <main class="{{ in_array(Route::currentRouteName(), ['login', 'register']) ? 'auth-layout' : 'py-4' }}">
            @yield('content')
        </main>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h4 class="font-weight-bold text-dark mb-2" id="confirmModalTitle">Are you sure?</h4>
                    <p class="text-muted mb-4" id="confirmModalMessage">This action cannot be undone.</p>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light px-4 mr-2 font-weight-bold" data-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                        <button type="button" class="btn btn-danger px-4 font-weight-bold" id="confirmModalAction" style="border-radius: 10px;">Confirm Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmAction(options) {
            const modal = $('#confirmModal');
            $('#confirmModalTitle').text(options.title || 'Are you sure?');
            $('#confirmModalMessage').text(options.message || 'This action cannot be undone.');
            $('#confirmModalAction').text(options.confirmText || 'Confirm').removeClass().addClass('btn px-4 font-weight-bold ' + (options.confirmClass || 'btn-danger'));
            
            $('#confirmModalAction').off('click').on('click', function() {
                options.onConfirm();
                modal.modal('hide');
            });
            
            modal.modal('show');
        }

        $(document).on('submit', 'form[data-confirm]', function(e) {
            e.preventDefault();
            const form = this;
            const message = $(form).data('confirm');
            const title = $(form).data('confirm-title');
            
            confirmAction({
                title: title || 'Confirm Action',
                message: message || 'Are you sure you want to proceed?',
                confirmText: 'Yes, Delete',
                confirmClass: 'btn-danger',
                onConfirm: function() {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
