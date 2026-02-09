@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4 mb-md-5">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle fa-4x"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-1">Document Verified</h2>
                        <p class="text-muted">This document is authentic and the digital signature is valid.</p>
                    </div>

                    <div class="document-details bg-light rounded-xl p-3 p-md-4 mb-4 mb-md-5" style="border-radius: 16px;">
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-3">
                            <i class="fas fa-info-circle me-2 text-primary"></i> Document Details
                        </h5>
                        
                        <div class="detail-row mb-3 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Nomor Surat:</div>
                            <div class="col-12 col-sm-8 text-dark px-0 fw-bold">{{ $document->document_number ?? 'N/A' }}</div>
                        </div>

                        <div class="detail-row mb-3 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Perihal:</div>
                            <div class="col-12 col-sm-8 text-dark px-0 fw-bold">{{ $document->perihal ?? 'N/A' }}</div>
                        </div>

                        <div class="detail-row mb-3 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">File Name:</div>
                            <div class="col-12 col-sm-8 text-dark px-0">{{ $document->file_name }}</div>
                        </div>

                        <div class="detail-row mb-3 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Signer Name:</div>
                            <div class="col-12 col-sm-8 text-dark px-0 fw-bold text-primary">{{ $document->signer ? $document->signer->name : 'N/A' }}</div>
                        </div>

                        <div class="detail-row mb-3 d-flex flex-wrap align-items-center">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Signer Role:</div>
                            <div class="col-12 col-sm-8 px-0">
                                <span class="badge bg-primary text-white px-3 py-2 text-wrap text-start" style="border-radius: 6px; font-size: 0.85rem; line-height: 1.4; max-width: 100%;">{{ $document->role }}</span>
                            </div>
                        </div>

                        <div class="detail-row mb-3 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Signed At:</div>
                            <div class="col-12 col-sm-8 text-dark px-0 fst-italic">{{ $document->updated_at->timezone('Asia/Jakarta')->format('l, d F Y - H:i:s') }}</div>
                        </div>

                        <div class="detail-row mb-0 d-flex flex-wrap">
                            <div class="col-12 col-sm-4 text-muted fw-bold px-0 mb-1 mb-sm-0">Verification Status:</div>
                            <div class="col-12 col-sm-8 px-0">
                                <span class="text-success fw-bold">
                                    <i class="fas fa-check-double me-1"></i> Authentic & Valid
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center border-top pt-4">
                        <p class="small text-muted mb-1">Verification ID</p>
                        <code class="bg-light px-3 py-2 rounded text-primary fw-bold">{{ request('uuid') ?? 'N/A' }}</code>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">&copy; {{ date('Y') }} Digital Signature. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-xl { border-radius: 1.5rem !important; }
    .detail-row { border-bottom: 1px solid rgba(0,0,0,0.03); padding-bottom: 0.75rem; }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
</style>
@endsection
