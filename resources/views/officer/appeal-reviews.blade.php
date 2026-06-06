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

    <div class="card">
        <div class="card-body">

            {{-- Search / Filter bar --}}
            <div class="d-flex align-items-center mb-3 gap-2">
                <label class="fw-semibold text-nowrap mb-0">Appeal Queue</label>
                <input type="text" id="appeal-search" class="form-control"
                    placeholder="Search compound or user..."
                    style="max-width: 380px;"
                    oninput="filterAppeals()">
            </div>

            <table class="table table-bordered table-hover mb-0" id="appeals-table">
                <thead class="table-light">
                    <tr>
                        <th>Compound ID</th>
                        <th>Plate No.</th>
                        <th>User</th>
                        <th>Offence</th>
                        <th>Full Amount</th>
                        <th>Discounted Amount</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appeals as $appeal)
                    <tr class="appeal-row"
                        data-search="{{ strtolower($appeal->compound->vehicle->plate_no . ' ' . $appeal->compound->vehicle->user->name . ' #C-' . str_pad($appeal->compound->id, 4, '0', STR_PAD_LEFT)) }}">
                        <td>
                            <strong>#C-{{ str_pad($appeal->compound->id, 4, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <span style="font-family:monospace;font-weight:600;">
                                {{ $appeal->compound->vehicle->plate_no }}
                            </span>
                        </td>
                        <td>{{ $appeal->compound->vehicle->user->name }}</td>
                        <td>{{ $appeal->compound->offenceType->name }}</td>
                        <td>{{ number_format($appeal->compound->offenceType->amount, 2) }}</td>
                        <td>{{ number_format($appeal->compound->offenceType->amount / 2, 2) }}</td>
                        <td style="max-width:200px;">
                            <span class="d-inline-block text-truncate" style="max-width:180px;" title="{{ $appeal->reason }}">
                                {{ $appeal->reason }}
                            </span>
                        </td>
                        <td>
                            @if($appeal->result === 'pending')
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('officer.appeal.update', $appeal) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="result" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success px-3"
                                            onclick="return confirm('Approve this appeal? The compound amount will be reduced by 50%.')">
                                            Accept
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('officer.appeal.update', $appeal) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="result" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger px-3"
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

        </div>
    </div>
</div>

<script>
function filterAppeals() {
    const query = document.getElementById('appeal-search').value.toLowerCase();
    const rows  = document.querySelectorAll('.appeal-row');
    let visible = 0;

    rows.forEach(row => {
        const match = row.dataset.search.includes(query);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const old = document.getElementById('no-results-row');
    if (old) old.remove();

    if (visible === 0 && rows.length > 0) {
        const tbody = document.querySelector('#appeals-table tbody');
        const tr = document.createElement('tr');
        tr.id = 'no-results-row';
        tr.innerHTML = '<td colspan="8" class="text-center text-muted py-3">No matching appeals found.</td>';
        tbody.appendChild(tr);
    }
}
</script>
@endsection