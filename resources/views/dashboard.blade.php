<!-- Made by :
Fauzan Reza Arnanda
fauzan.rez@gmail.com
https://www.linkedin.com/in/fauzan-reza/
https://fauzanreza.site/ -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 mb-md-5">
        <div class="mb-3 mb-md-0 text-center text-md-start">
            <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Manage and track your documents</p>
        </div>
        <div class="d-flex">
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('signer.index') }}" class="btn btn-sm btn-outline-primary shadow-sm px-3 me-2">
                <i class="fas fa-users-cog me-2"></i> Manage Signers
            </a>
            @endif
            <a href="{{ route('document.create') }}" class="btn btn-sm btn-primary shadow-sm px-3">
                <i class="fas fa-plus-circle me-2"></i> Upload Document
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center">
                <h5 class="mb-3 mb-lg-0 fw-bold text-dark"><i class="fas fa-folder-open me-2 text-primary"></i> Document History</h5>
                
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-column flex-md-row align-items-center w-100 w-lg-auto">
                    @if(request('role') || request('year') || request('search'))
                        <div class="mb-3 mb-md-0 me-md-3">
                            <a href="{{ route('dashboard') }}" class="text-muted small fw-bold">Clear Filters</a>
                        </div>
                    @endif
                    <div class="filter-group d-flex mb-3 mb-md-0 me-md-3 w-100 w-md-auto justify-content-center">
                        @if(Auth::user()->role === 'admin')
                        <select name="role" class="form-select border bg-light me-2 custom-select-minimal" style="border-radius: 10px; font-size: 0.85rem; width: 130px; height: 40px; border-color: #e5e7eb !important;" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @endif

                        <select name="year" class="form-select border bg-light custom-select-minimal" style="border-radius: 10px; font-size: 0.85rem; width: 110px; height: 40px; border-color: #e5e7eb !important;" onchange="this.form.submit()">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="search-wrapper w-100" style="position: relative; max-width: 300px;">
                        <i class="fas fa-search text-muted" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                        <input type="text" name="search" class="form-control border bg-light ps-5" placeholder="Search documents..." value="{{ request('search') }}" style="border-radius: 12px; font-size: 0.9rem; height: 40px; box-shadow: none; border-color: #e5e7eb !important;">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0 ps-4 py-3 text-muted small fw-bold text-uppercase">ID</th>
                                <th class="border-top-0 py-3 text-muted small fw-bold text-uppercase">File Name</th>
                                <th class="border-top-0 py-3 text-muted small fw-bold text-uppercase">Role</th>
                                <th class="border-top-0 py-3 text-muted small fw-bold text-uppercase">Status</th>
                                <th class="border-top-0 py-3 text-muted small fw-bold text-uppercase">Date</th>
                                <th class="border-top-0 py-3 text-center text-muted small fw-bold text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                                <tr>
                                    <td class="ps-4 align-middle fw-bold text-muted">#{{ $doc->id }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-danger">
                                                <i class="fas fa-file-pdf fa-lg"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $doc->file_name }}</span>
                                                @if($doc->document_number)
                                                    <small class="text-muted">{{ $doc->document_number }}</small>
                                                @endif
                                                @if($doc->perihal)
                                                    <small class="text-muted">{{ $doc->perihal }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><span class="badge bg-light text-dark border px-2 py-1">{{ ucfirst($doc->role) }}</span></td>
                                    <td class="align-middle">
                                        @if($doc->status == 'signed')
                                            <span class="badge bg-success px-2 py-1"><i class="fas fa-check-circle me-1"></i> Signed</span>
                                        @elseif($doc->status == 'pending')
                                            <span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">{{ ucfirst($doc->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-muted small">
                                        {{ $doc->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($doc->status == 'pending')
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="{{ route('document.position', $doc->id) }}" class="btn btn-sm btn-outline-primary shadow-sm py-1 px-2 me-2" style="font-size: 0.75rem;" title="Position QR">
                                                    Position QR
                                                </a>
                                                <form action="{{ route('document.destroy', $doc->id) }}" method="POST" data-confirm="Are you sure you want to delete this document? This will permanently remove the file." data-confirm-title="Delete Document">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm py-1 px-2" style="font-size: 0.75rem;" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($doc->status == 'signed')
                                            <a href="{{ route('document.download', $doc->id) }}" class="btn btn-sm btn-outline-success shadow-sm py-2 px-3" title="Download">
                                                <i class="fas fa-download fa-lg"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top">
                    {{ $documents->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-folder-open fa-3x text-muted opacity-50"></i>
                        </div>
                    </div>
                    <h5 class="text-dark fw-bold">No documents found</h5>
                    <p class="text-muted mb-4">
                        @if(request('role') || request('year') || request('search'))
                            No documents match your current filters.
                        @else
                            Upload a PDF document to get started with digital signatures.
                        @endif
                    </p>
                    @if(request('role') || request('year') || request('search'))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary px-4">
                            Clear All Filters
                        </a>
                    @else
                        <a href="{{ route('document.create') }}" class="btn btn-primary px-4">
                            Upload First Document
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-5 mb-4">
        <p class="text-muted small mb-0">&copy; {{ date('Y') }}. All rights reserved.</p>
        <!-- <small class="text-muted opacity-50">Securely sign and verify your documents with ease.</small> -->
    </div>
</div>
@push('styles')
<style>
    .search-wrapper .form-control:focus,
    .custom-select-minimal:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(158, 21, 32, 0.05) !important;
        border: 1px solid rgba(158, 21, 32, 0.1) !important;
        outline: none;
    }
    .custom-select-minimal {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px 10px;
        padding-right: 2rem !important;
    }

    /* Custom Pagination Styles */
    .pagination {
        margin-bottom: 0;
        display: flex;
        justify-content: center;
    }
    
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        background-color: #fff;
        transition: all 0.2s ease;
        box-shadow: none;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #9e1520 !important;
        border-color: #9e1520 !important;
        color: #fff !important;
        font-weight: 600;
    }
    
    .pagination .page-link:hover {
        background-color: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: #f9fafb;
        border-color: #f3f4f6;
        color: #9ca3af;
    }

    /* Target the specific Prev/Next items in Bootstrap pagination */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-weight: 500;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }

    /* Hide any default SVG icons that might still appear */
    .pagination svg {
        display: none !important;
    }

    /* Ensure only one pagination mode is visible (Bootstrap default) */
    .pagination-wrapper {
        display: block !important;
    }
    
    /* Mobile adjustments */
    @media (max-width: 576px) {
        .pagination .page-link {
            padding: 0.4rem 0.75rem;
            margin: 0 2px;
        }
    }
</style>
@endpush
@endsection
