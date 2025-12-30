@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="mb-0 font-weight-bold text-dark"><i class="fas fa-file-upload mr-2 text-primary"></i> Upload Document</h4>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('document.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="document_number" class="small font-weight-bold text-muted text-uppercase mb-2">Nomor Surat</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-hashtag text-primary"></i></span>
                                </div>
                                <input type="text" name="document_number" id="document_number" class="form-control @error('document_number') is-invalid @enderror" value="{{ old('document_number') }}" required placeholder="e.g. 123/UN37/LT/2023" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;">
                            </div>
                            @error('document_number')
                                <small class="text-danger font-weight-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="perihal" class="small font-weight-bold text-muted text-uppercase mb-2">Perihal</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-align-left text-primary"></i></span>
                                </div>
                                <input type="text" name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" value="{{ old('perihal') }}" required placeholder="e.g. Undangan Rapat" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;">
                            </div>
                            @error('perihal')
                                <small class="text-danger font-weight-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="signer_id" class="small font-weight-bold text-muted text-uppercase mb-2">Select Signer</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light" style="border-radius: 10px 0 0 10px;"><i class="fas fa-user-tie text-primary"></i></span>
                                </div>
                                <select name="signer_id" id="signer_id" class="form-control custom-select" style="height: auto; min-height: 45px; border-radius: 0 10px 10px 0;" required>
                                    <option value="">Choose the person who will sign</option>
                                    @foreach($signers as $signer)
                                        <option value="{{ $signer->id }}" {{ old('signer_id') == $signer->id ? 'selected' : '' }}>
                                            {{ $signer->name }} ({{ $signer->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('signer_id')
                                <small class="text-danger font-weight-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <a href="{{ route('signer.create') }}" class="small text-primary font-weight-bold">
                                    <i class="fas fa-plus-circle mr-1"></i> Add new signer
                                </a>
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="pdf_file" class="small font-weight-bold text-muted text-uppercase mb-2">PDF Document</label>
                            <div class="custom-file">
                                <input type="file" name="pdf_file" id="pdf_file" class="custom-file-input" accept=".pdf" required>
                                <label class="custom-file-label" for="pdf_file" style="height: calc(1.5em + 1rem + 2px); padding: .5rem 1rem;">Choose PDF file...</label>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1 text-primary"></i> Only PDF files are allowed. Max size: 10MB.
                            </small>
                            @error('pdf_file')
                                <small class="text-danger font-weight-bold mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5">
                            <a href="{{ route('dashboard') }}" class="btn btn-light text-muted font-weight-bold px-4 w-100 w-md-auto mb-3 mb-md-0 order-2 order-md-1">
                                <i class="fas fa-arrow-left mr-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4 font-weight-bold w-100 w-md-auto order-1 order-md-2" style="border-radius: 8px;">
                                Next: Position QR <i class="fas fa-arrow-right ml-2"></i>
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

<style>
    .custom-file-label::after {
        height: calc(1.5em + 1rem);
        padding: .5rem 1rem;
        line-height: 1.5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('pdf_file');
        const fileLabel = document.querySelector('.custom-file-label');
        
        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                fileLabel.textContent = e.target.files[0].name;
            }
        });
    });
</script>
@endsection