@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark mb-3 mb-md-0">Manage Signers</h2>
        <a href="{{ route('signer.create') }}" class="btn btn-sm btn-primary shadow-sm px-3 w-100 w-md-auto">
            <i class="fas fa-plus mr-2"></i> Add New Signer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">Name</th>
                            <th class="border-0 px-4 py-3">Role</th>
                            <th class="border-0 px-4 py-3">Email</th>
                            <th class="border-0 px-4 py-3">Created At</th>
                            <th class="border-0 px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signers as $signer)
                            <tr>
                                <td class="px-4 py-3 font-weight-bold text-dark">{{ $signer->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-light text-primary px-3 py-2" style="border-radius: 8px;">
                                        {{ $signer->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-muted">{{ $signer->user->email ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-muted">{{ $signer->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('signer.edit', $signer) }}" class="btn btn-sm btn-outline-primary mr-2" style="border-radius: 8px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('signer.destroy', $signer) }}" method="POST" data-confirm="Are you sure you want to delete this signer? This may affect documents assigned to them." data-confirm-title="Delete Signer">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                    <p>No signers found. Start by adding one!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $signers->links() }}
    </div>

    <div class="text-center mt-5 mb-4">
        <p class="text-muted small mb-0">&copy; {{ date('Y') }}. All rights reserved.</p>
        <!-- <small class="text-muted opacity-50">Securely sign and verify your documents with ease.</small> -->
    </div>
</div>
@endsection
