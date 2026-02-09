@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fas fa-file-upload me-2 text-primary"></i> Upload Document</h4>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('document.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="document_number" class="small fw-bold text-muted text-uppercase mb-2">Nomor Surat</label>
                            <div class="input-group">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-hashtag text-primary"></i></span>
                                <input type="text" name="document_number" id="document_number" class="form-control @error('document_number') is-invalid @enderror" value="{{ old('document_number') }}" required placeholder="e.g. 123/UN37/LT/2023" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;">
                            </div>
                            @error('document_number')
                                <small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="perihal" class="small fw-bold text-muted text-uppercase mb-2">Perihal</label>
                            <div class="input-group">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-align-left text-primary"></i></span>
                                <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" required placeholder="e.g. Undangan Rapat" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;">
                            </div>
                            @error('perihal')
                                <small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        @if(Auth::user()->role === 'admin')
                        <div class="form-group mb-4">
                            <label for="signer_id" class="small fw-bold text-muted text-uppercase mb-2">Select Signer</label>
                            <div class="input-group">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-user-tie text-primary"></i></span>
                                <select name="signer_id" id="signer_id" class="form-select" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;" required>
                                    <option value="">Choose the person who will sign</option>
                                    @foreach($signers as $signer)
                                        <option value="{{ $signer->id }}" {{ old('signer_id') == $signer->id ? 'selected' : '' }}>
                                            {{ $signer->name }} ({{ $signer->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('signer_id')
                                <small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <a href="{{ route('signer.create') }}" class="small text-primary fw-bold">
                                    <i class="fas fa-plus-circle me-1"></i> Add new signer
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="form-group mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2">Role</label>
                            <div class="input-group">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-user-tag text-primary"></i></span>
                                <input type="text" class="form-control" value="{{ Auth::user()->signer->role ?? 'No Role Assigned' }}" readonly style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0; background-color: #f8f9fa;">
                                <input type="hidden" name="signer_id" value="{{ Auth::user()->signer->id ?? '' }}">
                            </div>
                            @if(!Auth::user()->signer)
                                <small class="text-danger fw-bold mt-1 d-block">Warning: Your account is not linked to a signer record. Please contact admin.</small>
                            @endif
                        </div>
                        @endif
                        
                        <div class="form-group mb-4">
                            <label for="pdf_file" class="small fw-bold text-muted text-uppercase mb-2">PDF Document</label>
                            <div class="mb-3">
                                <input class="form-control" type="file" name="pdf_file" id="pdf_file" accept=".pdf" required style="height: auto; padding: .5rem 1rem;">
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Only PDF files are allowed. Max size: 10MB.
                            </small>
                            @error('pdf_file')
                                <small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5">
                            <a href="{{ route('dashboard') }}" class="btn btn-light text-muted fw-bold px-4 w-100 w-md-auto mb-3 mb-md-0 order-2 order-md-1">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4 fw-bold w-100 w-md-auto order-1 order-md-2" style="border-radius: 8px;">
                                Next: Position QR <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 mb-4">
        <p class="text-muted small mb-0">&copy; {{ date('Y') }}. All rights reserved.</p>
        <!-- <small class="text-muted opacity-50">Securely sign and verify your documents with ease.</small> -->
    </div>
</div>

@endsection