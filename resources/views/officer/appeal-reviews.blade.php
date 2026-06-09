@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Appeal Reviews</strong></h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters Card --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted fw-semibold me-2">Appeal Queue</span>
                <input type="text" id="appeal-search" class="form-control form-control-sm"
                    placeholder="Search ID, plate, name, or matric no..."
                    style="max-width: 380px;">
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="appeals-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Compound ID</th>
                        <th>Plate No.</th>
                        <th>User</th>
                        <th>Matric/Staff No.</th>
                        <th>Offence</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appeals as $appeal)
                    <tr class="appeal-row"
                        data-search="{{ strtolower($appeal->compound->vehicle->plate_no . ' ' . $appeal->compound->vehicle->user->name . ' ' . ($appeal->compound->vehicle->user->matric_or_staff_no ?? '') . ' #c-' . str_pad($appeal->compound->id, 4, '0', STR_PAD_LEFT)) }}">
                        
                        <td class="ps-4">
                            <strong>#C-{{ str_pad($appeal->compound->id, 4, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <span class="fw-bold font-monospace text-dark">
                                {{ strtoupper($appeal->compound->vehicle->plate_no) }}
                            </span>
                        </td>
                        <td>{{ $appeal->compound->vehicle->user->name }}</td>
                        <td>
                            @if($appeal->compound->vehicle->user->matric_or_staff_no)
                                {{ $appeal->compound->vehicle->user->matric_or_staff_no }}
                            @else
                                <span class="badge bg-secondary">External</span>
                            @endif
                        </td>
                        <td>{{ $appeal->compound->offenceType->name }}</td>
                        <td>{{ number_format($appeal->compound->offenceType->amount, 2) }}</td>
                        <td style="max-width: 200px;">
                            <span class="d-inline-block text-truncate" style="max-width: 180px; cursor: help;" title="{{ $appeal->reason }}">
                                {{ $appeal->reason }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            @if($appeal->result === 'pending')
                                <div class="d-flex justify-content-end gap-1">
                                    <form method="POST" action="{{ route('officer.appeal.update', $appeal) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="result" value="approved">
                                        <button type="submit" class="btn btn-sm btn-outline-success px-3"
                                            onclick="return confirm('Approve this appeal? The compound amount will be reduced by 50%.')">
                                            Accept
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('officer.appeal.update', $appeal) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="result" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3"
                                            onclick="return confirm('Reject this appeal?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @elseif($appeal->result === 'approved')
                                <span class="badge bg-success">Accepted</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No pending appeals found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" class="text-center text-muted py-4" style="display:none;">
                No matching appeals found.
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('appeal-search');
        const rows = document.querySelectorAll('.appeal-row');
        const noResults = document.getElementById('noResults');

        function filterAppeals() {
            const query = searchInput.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach(row => {
                const match = row.dataset.search.includes(query);
                if (match) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visible === 0 ? 'block' : 'none';
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterAppeals);
        }
    });
</script>
@endsection