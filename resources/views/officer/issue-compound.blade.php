@extends('master.layout')
 
@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Issue Compound</strong></h1>
 
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
 
    {{-- ── Filter / Search bar ── --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label mb-1 small fw-semibold">Compound No.</label>
                    <input type="text" id="filter-compound" class="form-control form-control-sm"
                        placeholder="Enter compound no." style="width:170px" oninput="filterTable()">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1 small fw-semibold">Vehicle No.</label>
                    <input type="text" id="filter-vehicle" class="form-control form-control-sm"
                        placeholder="Enter vehicle no." style="width:170px" oninput="filterTable()">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1 small fw-semibold">Issue Date</label>
                    <input type="date" id="filter-date" class="form-control form-control-sm"
                        style="width:170px" oninput="filterTable()">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm px-4" onclick="filterTable()">Search</button>
                </div>
                <div class="col-auto ms-auto">
                    <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#issueModal">
                        + Issue New Compound
                    </button>
                </div>
            </div>
        </div>
    </div>
 
    {{-- ── Compounds table ── --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0" id="compounds-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width:50px">NO.</th>
                        <th>COMPOUND NO.</th>
                        <th>VEHICLE NO.</th>
                        <th>OFFENCE TYPE</th>
                        <th>ISSUE DATE</th>
                        <th>AMOUNT (RM)</th>
                        <th>STATUS</th>
                        <th class="pe-3">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compounds as $compound)
                    <tr class="compound-row"
                        data-compound="cmp{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}"
                        data-vehicle="{{ strtolower($compound->vehicle->plate_no) }}"
                        data-date="{{ $compound->issued_at ? $compound->issued_at->format('Y-m-d') : '' }}">
                        <td class="ps-3 text-center">{{ $loop->iteration }}</td>
                        <td><strong>CMP{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td style="font-family:monospace;font-weight:600;">{{ $compound->vehicle->plate_no }}</td>
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
                        <td class="pe-3">
                            <div class="d-flex gap-1">
                                <button class="btn btn-primary btn-sm"
                                    onclick="viewCompound({{ $compound->id }})">
                                    VIEW
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $compound->id }}, 'CMP{{ str_pad($compound->id, 6, '0', STR_PAD_LEFT) }}')">
                                    DELETE
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No compounds issued yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($compounds->count())
        <div class="card-footer text-muted small">
            Showing 1 to {{ $compounds->count() }} of {{ $compounds->count() }} entries
        </div>
        @endif
    </div>
</div>
 
{{-- ════════════════════════════════════════════════
     Issue New Compound Modal (3-step wizard)
════════════════════════════════════════════════ --}}
<div class="modal fade" id="issueModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
 
            {{-- Step 1 --}}
            <div id="modal-step-1">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Compound — Step 1 of 3</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetModal()"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Plate No.</label>
                    <input type="text" id="plate-input" class="form-control"
                        placeholder="e.g. WQR 4421"
                        style="text-transform:uppercase;font-family:monospace;font-size:18px">
                    <div class="text-danger small mt-1 d-none" id="plate-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" id="lookup-btn" onclick="lookupPlate()">
                        <span id="lookup-spinner" class="spinner-border spinner-border-sm d-none me-1"></span>
                        Look Up
                    </button>
                </div>
            </div>
 
            {{-- Step 2 --}}
            <div id="modal-step-2" class="d-none">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Compound — Step 2 of 3</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-sm mb-3">
                        <tr><th style="width:130px">Plate</th><td id="info-plate">—</td></tr>
                        <tr><th>Owner</th><td id="info-owner">—</td></tr>
                        <tr><th>Type</th><td id="info-type">—</td></tr>
                        <tr><th>Sticker Status</th><td id="info-sticker">—</td></tr>
                    </table>
                    <label class="form-label fw-semibold">Select Offence</label>
                    <select class="form-select" id="offence-select">
                        <option value="">-- Select Offence --</option>
                        @foreach($offenceTypes as $offence)
                            <option value="{{ $offence->id }}"
                                data-amount="{{ $offence->amount }}"
                                data-name="{{ $offence->name }}">
                                {{ $offence->name }} — RM {{ number_format($offence->amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger small mt-1 d-none" id="offence-error">Please select an offence.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)">Back</button>
                    <button type="button" class="btn btn-primary" onclick="goToStep(3)">Next</button>
                </div>
            </div>
 
            {{-- Step 3 --}}
            <div id="modal-step-3" class="d-none">
                <div class="modal-header">
                    <h5 class="modal-title">Issue Compound — Step 3 of 3</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Please verify the details before issuing. This cannot be undone.
                    </div>
                    <table class="table table-sm mb-0">
                        <tr><th style="width:130px">Plate</th><td id="confirm-plate">—</td></tr>
                        <tr><th>Owner</th><td id="confirm-owner">—</td></tr>
                        <tr><th>Offence</th><td id="confirm-offence">—</td></tr>
                        <tr><th>Amount</th><td><strong id="confirm-amount">—</strong></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('officer.compound.store') }}" id="issue-form">
                        @csrf
                        <input type="hidden" name="vehicle_id" id="hidden-vehicle-id">
                        <input type="hidden" name="offence_type_id" id="hidden-offence-id">
                        <button type="button" class="btn btn-secondary me-2" onclick="goToStep(2)">Back</button>
                        <button type="submit" class="btn btn-danger">Issue Compound</button>
                    </form>
                </div>
            </div>
 
        </div>
    </div>
</div>
 
{{-- Edit / View Compound Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Compound — <span id="edit-compound-ref"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="edit-form">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <table class="table table-sm mb-3">
                        <tr><th style="width:140px">Vehicle No.</th><td id="edit-plate" style="font-family:monospace;font-weight:600"></td></tr>
                        <tr><th>Owner</th><td id="edit-owner"></td></tr>
                        <tr><th>Issue Date</th><td id="edit-date"></td></tr>
                        <tr><th>Issued By</th><td id="edit-officer"></td></tr>
                    </table>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Offence Type</label>
                        <select class="form-select" name="offence_type_id" id="edit-offence-select">
                            @foreach($offenceTypes as $offence)
                                <option value="{{ $offence->id }}"
                                    data-amount="{{ $offence->amount }}">
                                    {{ $offence->name }} — RM {{ number_format($offence->amount, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="mb-3">
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
 
{{-- Delete Confirm Modal --}}
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
                <form method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
 
<script>
// ── Issue wizard ──────────────────────────────────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
 
function goToStep(n) {
    [1, 2, 3].forEach(i => {
        document.getElementById('modal-step-' + i).classList.toggle('d-none', i !== n);
    });
}
 
async function lookupPlate() {
    const plate  = document.getElementById('plate-input').value.trim().toUpperCase();
    const errEl  = document.getElementById('plate-error');
    errEl.classList.add('d-none');
 
    if (!plate) {
        errEl.textContent = 'Please enter a plate number.';
        errEl.classList.remove('d-none');
        return;
    }
 
    const btn     = document.getElementById('lookup-btn');
    const spinner = document.getElementById('lookup-spinner');
    btn.disabled  = true;
    spinner.classList.remove('d-none');
 
    try {
        const res  = await fetch('{{ route('officer.compound.lookup') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ plate_no: plate }),
        });
        const data = await res.json();
 
        if (!data.found) {
            errEl.textContent = data.message || 'Vehicle not found.';
            errEl.classList.remove('d-none');
        } else {
            document.getElementById('info-plate').textContent = data.plate;
            document.getElementById('info-owner').textContent = data.owner;
            document.getElementById('info-type').textContent  = data.type;
            document.getElementById('info-sticker').innerHTML =
                data.sticker === 'Valid'
                    ? '<span class="badge bg-success">Valid</span>'
                    : '<span class="badge bg-danger">Invalid / Not Registered</span>';
            document.getElementById('hidden-vehicle-id').value = data.id;
            goToStep(2);
        }
    } catch (e) {
        errEl.textContent = 'An error occurred. Please try again.';
        errEl.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
    }
}
 
function goToStep(n) {
    if (n === 3) {
        const sel    = document.getElementById('offence-select');
        const errEl  = document.getElementById('offence-error');
        if (!sel.value) { errEl.classList.remove('d-none'); return; }
        errEl.classList.add('d-none');
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('confirm-plate').textContent   = document.getElementById('info-plate').textContent;
        document.getElementById('confirm-owner').textContent   = document.getElementById('info-owner').textContent;
        document.getElementById('confirm-offence').textContent = opt.dataset.name;
        document.getElementById('confirm-amount').textContent  = 'RM ' + parseFloat(opt.dataset.amount).toFixed(2);
        document.getElementById('hidden-offence-id').value     = sel.value;
    }
    [1, 2, 3].forEach(i =>
        document.getElementById('modal-step-' + i).classList.toggle('d-none', i !== n)
    );
}
 
function resetModal() {
    document.getElementById('plate-input').value = '';
    document.getElementById('plate-error').classList.add('d-none');
    document.getElementById('offence-select').value = '';
    document.getElementById('hidden-vehicle-id').value = '';
    document.getElementById('hidden-offence-id').value = '';
    goToStep(1);
}
 
document.getElementById('plate-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') lookupPlate();
});
 
// Reset modal when closed
document.getElementById('issueModal').addEventListener('hidden.bs.modal', resetModal);
 
// ── Table filter ──────────────────────────────────────────────────────────────
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
 
// ── View / Edit compound ──────────────────────────────────────────────────────
const compoundData = @json($compounds->load('vehicle.user', 'offenceType', 'officer')->keyBy('id'));
 
function viewCompound(id) {
    const c = compoundData[id];
    if (!c) return;
 
    document.getElementById('edit-compound-ref').textContent = 'CMP' + String(c.id).padStart(6, '0');
    document.getElementById('edit-plate').textContent    = c.vehicle?.plate_no ?? '—';
    document.getElementById('edit-owner').textContent    = c.vehicle?.user?.name ?? '—';
    document.getElementById('edit-date').textContent     = c.issued_at ? new Date(c.issued_at).toLocaleDateString('en-GB') : '—';
    document.getElementById('edit-officer').textContent  = c.officer?.name ?? '—';
 
    // Set offence select
    const offSel = document.getElementById('edit-offence-select');
    offSel.value = c.offence_type_id;
 
    // Set status select
    document.getElementById('edit-status').value = c.status;
 
    // Point form to update route
    document.getElementById('edit-form').action = `/officer/compound/${c.id}`;
 
    new bootstrap.Modal(document.getElementById('viewModal')).show();
}
 
// ── Delete compound ───────────────────────────────────────────────────────────
function confirmDelete(id, ref) {
    document.getElementById('delete-ref').textContent  = ref;
    document.getElementById('delete-form').action      = `/officer/compound/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection