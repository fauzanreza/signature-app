@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('signer.index') }}" class="btn btn-link text-dark p-0 mr-3">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <h2 class="font-weight-bold text-dark mb-0">Edit Signer</h2>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <form action="{{ route('signer.update', $signer) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label for="name" class="small font-weight-bold text-muted text-uppercase mb-2">Signer Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $signer->name) }}" required placeholder="e.g. Dr. John Doe">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="role" class="small font-weight-bold text-muted text-uppercase mb-2">Role / Position</label>
                            <input type="text" name="role" id="role" class="form-control form-control-lg @error('role') is-invalid @enderror" value="{{ old('role', $signer->role) }}" required placeholder="e.g. Director of Research">
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-save mr-2"></i> Update Signer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
