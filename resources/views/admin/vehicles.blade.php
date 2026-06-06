@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>All Registered Vehicles</strong></h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            {{-- Filters Row --}}
            <div class="d-flex gap-2 mb-3 align-items-center flex-wrap">
                <select class="form-select form-select-sm w-auto" id="filterType">
                    <option value="">All Types</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                 
                </select>

                <select class="form-select form-select-sm w-auto" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>

                <input type="text" class="form-control form-control-sm w-auto" id="searchPlate" placeholder="Search Plate Number...">

                <a href="{{ route('admin.vehicles') }}" class="btn btn-sm btn-warning ms-auto text-dark fw-semibold">
                    + Add New Offence
                </a>
            </div>

            {{-- Table --}}
            <table class="table table-hover align-middle" id="vehiclesTable">
                <thead>
                    <tr>
                        <th>Plate No.</th>
                        <th>Type</th>
                        <th>Owner Name</th>
                        <th>Matric/Staff No.</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr
                            data-type="{{ $vehicle->type }}"
                            data-status="{{ $vehicle->status }}"
                            data-plate="{{ strtolower($vehicle->plate_no) }}"
                        >
                            <td><code>{{ strtoupper($vehicle->plate_no) }}</code></td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td>{{ $vehicle->user->name }}</td>
                            <td>{{ $vehicle->user->matric_no ?? $vehicle->user->staff_no ?? '—' }}</td>
                            <td>{{ $vehicle->reason }}</td>
                            <td>
                                @if($vehicle->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($vehicle->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicle->status === 'pending')
                                    <form action="{{ route('admin.vehicles.approve', $vehicle) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.vehicles.reject', $vehicle) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="text-center text-muted py-3">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- No results message (shown by JS when filter returns nothing) --}}
            <div id="noResults" class="text-center text-muted py-3" style="display:none;">
                No vehicles match your search.
            </div>

        </div>
    </div>
</div>

<script>
    const typeFilter   = document.getElementById('filterType');
    const statusFilter = document.getElementById('filterStatus');
    const searchInput  = document.getElementById('searchPlate');
    const rows         = document.querySelectorAll('#vehiclesTable tbody tr[data-plate]');
    const noResults    = document.getElementById('noResults');

    function applyFilters() {
        const type   = typeFilter.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const plate  = searchInput.value.toLowerCase();
        let visible  = 0;

        rows.forEach(row => {
            const matchType   = !type   || row.dataset.type   === type;
            const matchStatus = !status || row.dataset.status === status;
            const matchPlate  = !plate  || row.dataset.plate.includes(plate);

            if (matchType && matchStatus && matchPlate) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    typeFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
</script>

@endsection