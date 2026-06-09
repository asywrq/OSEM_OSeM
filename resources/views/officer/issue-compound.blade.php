@extends('master.layout')

@section('content')

<style>
    .filter-input { max-width: 170px; }
    .plate-input-lg { text-transform: uppercase; font-family: monospace; font-size: 18px; }
    .plate-display { font-family: monospace; font-weight: 600; }
    .detail-th { width: 130px; }
</style>

<div class="container-fluid p-0">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>Issue Compound</strong></h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issueModal">
            + Issue New Compound
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter bar --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted fw-semibold me-2">Filter Records</span>
                <input type="text" id="filter-compound" class="form-control form-control-sm filter-input"
                    placeholder="Compound no..." oninput="filterTable()">
                <input type="text" id="filter-vehicle" class="form-control form-control-sm filter-input"
                    placeholder="Vehicle no..." oninput="filterTable()">
                <input type="date" id="filter-date" class="form-control form-control-sm filter-input"
                    oninput="filterTable()">
                <button type="button" class="btn btn-sm btn-secondary ms-auto" onclick="clearFilters()">Clear Filters</button>
            </div>
        </div>
    </div>

    {{-- Compounds table --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="compounds-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">COMPOUND NO.</th>
                        <th>VEHICLE NO.</th>
                        <th>OFFENCE TYPE</th>
                        <th>ISSUE DATE</th>
                        <th>AMOUNT (RM)</th>
                        <th>STATUS</th>
                        <th class="pe-4 text-end">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compounds as $compound)
                    <tr class="compound-row"
                        data-compound="cmp{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}"
                        data-vehicle="{{ strtolower($compound->vehicle->plate_no) }}"
                        data-date="{{ $compound->issued_at ? $compound->issued_at->format('Y-m-d') : '' }}">
                        <td class="ps-4"><strong>CMP{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td><span class="plate-display text-dark">{{ strtoupper($compound->vehicle->plate_no) }}</span></td>
                        <td>{{ $compound->offenceType->name }}</td>
                        <td>{{ $compound->issued_at ? $compound->issued_at->format('d/m/Y') : '—' }}</td>
                        <td>
                            @if($compound->is_discounted)
                                <span class="text-muted text-decoration-line-through me-1">
                                    {{ number_format($compound->offenceType->amount, 2) }}
                                </span>
                                {{ number_format($compound->offenceType->amount / 2, 2) }}
                            @else
                                {{ number_format($compound->offenceType->amount, 2) }}
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeMap = [
                                    'unpaid'    => ['bg-warning text-dark', 'UNPAID'],
                                    'appealing' => ['bg-info text-dark',    'APPEALING'],
                                    'paid'      => ['bg-success',           'PAID'],
                                    'resolved'  => ['bg-secondary',         'RESOLVED'],
                                ];
                                [$cls, $label] = $badgeMap[$compound->status] ?? ['bg-secondary', strtoupper($compound->status)];
                            @endphp
                            <span class="badge {{ $cls }}">{{ $label }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-outline-primary btn-sm px-3"
                                    data-bs-toggle="modal" data-bs-target="#viewModal"
                                    onclick="openEdit(
                                        {{ $compound->id }},
                                        'CMP{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}',
                                        '{{ strtoupper($compound->vehicle->plate_no) }}',
                                        {{ $compound->offence_type_id }},
                                        '{{ $compound->status }}'
                                    )">View</button>
                                <button class="btn btn-outline-danger btn-sm px-3"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    onclick="openDelete(
                                        {{ $compound->id }},
                                        'CMP{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}'
                                    )">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No compounds issued yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($compounds->count())
        <div class="card-footer text-muted small">
            Showing {{ $compounds->count() }} entries
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="issueModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Step 1: Plate lookup --}}
            <div id="step-1">
                <form method="POST" action="{{ route('officer.compound.lookup') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Issue Compound — Step 1 of 3</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-semibold">Plate No.</label>
                        <input type="text" name="plate_no" class="form-control plate-input-lg"
                            placeholder="e.g. WQR 4421"
                            value="{{ session('lookup_plate') }}"
                            required>
                        @if(session('lookup_error'))
                            <div class="text-danger small mt-1">{{ session('lookup_error') }}</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Look Up</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Step 2+3 Modal (shown after successful lookup) --}}
@if(session('lookup_vehicle'))
@php $lv = session('lookup_vehicle'); @endphp
<div class="modal fade" id="issueStep2Modal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Step 2 --}}
            <div id="step-2-inner">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Compound — Step 2 of 3</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-sm mb-3">
                        <tr><th class="detail-th">Plate</th><td class="plate-display">{{ strtoupper($lv['plate']) }}</td></tr>
                        <tr><th>Owner</th><td>{{ $lv['owner'] }}</td></tr>
                        <tr><th>Type</th><td>{{ ucfirst($lv['type']) }}</td></tr>
                        <tr><th>Sticker</th><td><span class="badge bg-success">Valid</span></td></tr>
                    </table>
                    <label class="form-label fw-semibold">Select Offence</label>
                    <select class="form-select" id="offence-sel" onchange="updateStep3()">
                        <option value="">-- Select Offence --</option>
                        @foreach($offenceTypes as $offence)
                            <option value="{{ $offence->id }}"
                                data-amount="{{ $offence->amount }}"
                                data-name="{{ $offence->name }}">
                                {{ $offence->name }} — RM {{ number_format($offence->amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger small mt-1 d-none" id="offence-err">Please select an offence.</div>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('officer.compound.clear_lookup') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Back</button>
                    </form>
                    <button type="button" class="btn btn-primary" onclick="showStep3()">Next</button>
                </div>
            </div>

            {{-- Step 3 --}}
            <div id="step-3-inner" class="d-none">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Compound — Step 3 of 3</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Please verify the details before issuing. This cannot be undone.
                    </div>
                    <table class="table table-sm mb-0">
                        <tr><th class="detail-th">Plate</th><td class="plate-display">{{ strtoupper($lv['plate']) }}</td></tr>
                        <tr><th>Owner</th><td>{{ $lv['owner'] }}</td></tr>
                        <tr><th>Offence</th><td id="confirm-offence">—</td></tr>
                        <tr><th>Amount</th><td><strong id="confirm-amount">—</strong></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="backToStep2()">Back</button>
                    <form method="POST" action="{{ route('officer.compound.store') }}">
                        @csrf
                        <input type="hidden" name="vehicle_id" value="{{ $lv['id'] }}">
                        <input type="hidden" name="offence_type_id" id="hidden-offence-id">
                        <button type="submit" class="btn btn-danger">Issue Compound</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('issueStep2Modal')).show();
    });
    function showStep3() {
        const sel = document.getElementById('offence-sel');
        if (!sel.value) {
            document.getElementById('offence-err').classList.remove('d-none');
            return;
        }
        document.getElementById('offence-err').classList.add('d-none');
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('confirm-offence').textContent = opt.dataset.name;
        document.getElementById('confirm-amount').textContent  = 'RM ' + parseFloat(opt.dataset.amount).toFixed(2);
        document.getElementById('hidden-offence-id').value     = sel.value;
        document.getElementById('step-2-inner').classList.add('d-none');
        document.getElementById('step-3-inner').classList.remove('d-none');
    }
    function backToStep2() {
        document.getElementById('step-3-inner').classList.add('d-none');
        document.getElementById('step-2-inner').classList.remove('d-none');
    }
    function updateStep3() {}
</script>
@endif

{{-- Edit Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Compound — <span id="edit-ref"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('officer.compound.update') }}">
                @csrf
                <input type="hidden" name="compound_id" id="edit-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Vehicle No.</label>
                        <input type="text" class="form-control text-uppercase font-monospace" name="plate_no" id="edit-plate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Offence Type</label>
                        <select class="form-select" name="offence_type_id" id="edit-offence">
                            @foreach($offenceTypes as $offence)
                                <option value="{{ $offence->id }}">
                                    {{ $offence->name }} — RM {{ number_format($offence->amount, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="edit-status">
                            <option value="unpaid">Unpaid</option>
                            <option value="appealing">Appealing</option>
                            <option value="paid">Paid</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger">Delete Compound</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-1">
                Are you sure you want to delete <strong id="delete-ref"></strong>? This cannot be undone.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('officer.compound.destroy') }}">
                    @csrf
                    <input type="hidden" name="compound_id" id="delete-id">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Table filter
function filterTable() {
    const compound = document.getElementById('filter-compound').value.toLowerCase().replace(/\s/g,'');
    const vehicle  = document.getElementById('filter-vehicle').value.toLowerCase().replace(/\s/g,'');
    const date     = document.getElementById('filter-date').value;

    document.querySelectorAll('.compound-row').forEach(row => {
        const matchC = !compound || row.dataset.compound.includes(compound);
        const matchV = !vehicle  || row.dataset.vehicle.replace(/\s/g,'').includes(vehicle);
        const matchD = !date     || row.dataset.date === date;
        row.style.display = (matchC && matchV && matchD) ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('filter-compound').value = '';
    document.getElementById('filter-vehicle').value = '';
    document.getElementById('filter-date').value = '';
    filterTable();
}

// Edit modal 
function openEdit(id, ref, plate, offenceId, status) {
    document.getElementById('edit-ref').textContent    = ref;
    document.getElementById('edit-id').value           = id;
    document.getElementById('edit-plate').value        = plate;
    document.getElementById('edit-offence').value      = offenceId;
    document.getElementById('edit-status').value       = status;
}

// Delete modal 
function openDelete(id, ref) {
    document.getElementById('delete-ref').textContent = ref;
    document.getElementById('delete-id').value        = id;
}
</script>
@endsection