@extends('master.layout')

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>Offence Types</strong></h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOffenceModal">
            + Add Offence
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Offences</th>
                        <th>Amount (RM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offenceTypes as $offence)
                    <tr>
                        <td>{{ $offence->name }}</td>
                        <td>RM {{ number_format($offence->amount, 2) }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editOffenceModal"
                                data-id="{{ $offence->id }}"
                                data-name="{{ $offence->name }}"
                                data-amount="{{ $offence->amount }}">
                                Edit
                            </button>
                            <form method="POST"
                                action="{{ route('admin.offences.destroy', $offence->id) }}"
                                class="d-inline"
                                onsubmit="return confirm('Delete this offence type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No offence types found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Edit Modal (per row) --}}
<div class="modal fade" id="editOffenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editOffenceForm" action="">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Offence Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Offence Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_offence_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="edit_offence_amount" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Offence Modal --}}
<div class="modal fade" id="addOffenceModal" tabindex="-1" aria-labelledby="addOffenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.offences.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addOffenceModalLabel">Add Offence Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Offence Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            value="{{ old('amount') }}" step="0.01" min="0" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Offence</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editOffenceModal = document.getElementById('editOffenceModal');
        if (editOffenceModal) {
            editOffenceModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                
                const form = document.getElementById('editOffenceForm');
                const baseRoute = "{{ route('admin.offences.update', 'ROUTE_ID') }}";
                form.action = baseRoute.replace('ROUTE_ID', id);
                
                document.getElementById('edit_offence_name').value = name;
                document.getElementById('edit_offence_amount').value = amount;
            });
        }
    });
</script>

{{-- Re-open add modal if validation failed --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addOffenceModal')).show();
    });
</script>
@endif

@endsection