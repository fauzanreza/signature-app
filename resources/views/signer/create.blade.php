@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('signer.index') }}" class="btn btn-link text-dark p-0 me-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h2 class="fw-bold text-dark mb-0">Add New Signer</h2>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <form action="{{ route('signer.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="name" class="small fw-bold text-muted text-uppercase mb-2">Signer Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Dr. John Doe">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="role" class="small fw-bold text-muted text-uppercase mb-2">Role / Position</label>
                            <input type="text" name="role" id="role" class="form-control form-control-lg @error('role') is-invalid @enderror" value="{{ old('role') }}" required placeholder="e.g. Director of Research">
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="username" class="small fw-bold text-muted text-uppercase mb-2">Username</label>
                            <input type="text" name="username" id="username" class="form-control form-control-lg @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="e.g. johndoe">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="email" class="small fw-bold text-muted text-uppercase mb-2">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="e.g. john.doe@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="small fw-bold text-muted text-uppercase mb-2">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="small fw-bold text-muted text-uppercase mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" required placeholder="Re-type password">
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-save me-2"></i> Create Signer Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
