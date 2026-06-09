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

    {{-- Filters Card --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="text-muted fw-semibold me-2">Filter Vehicles</span>
                
                <select class="form-select form-select-sm w-auto" id="filterType">
                    <option value="">All Types</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                    <option value="van">Van</option>
                </select>

                <select class="form-select form-select-sm w-auto" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>

                <input type="text" class="form-control form-control-sm" id="searchVehicle" 
                    placeholder="Search plate or matric no..." style="max-width: 300px;">
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="vehiclesTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Plate No.</th>
                        <th>Type</th>
                        <th>Owner Name</th>
                        <th>Matric/Staff No.</th>
                        <th>Reason</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr
                            class="vehicle-row"
                            data-type="{{ $vehicle->type }}"
                            data-status="{{ $vehicle->status }}"
                            data-plate="{{ strtolower($vehicle->plate_no) }}"
                            data-matric="{{ strtolower($vehicle->user->matric_or_staff_no ?? '') }}"
                        >
                            <td class="ps-4">
                                <span class="fw-bold font-monospace text-dark">{{ strtoupper($vehicle->plate_no) }}</span>
                            </td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td>{{ $vehicle->user->name }}</td>
                            <td>
                                @if($vehicle->user->matric_or_staff_no)
                                    {{ $vehicle->user->matric_or_staff_no }}
                                @else
                                    <span class="badge bg-secondary">External</span>
                                @endif
                            </td>
                            <td style="max-width: 200px;">
                                <span class="d-inline-block text-truncate" style="max-width: 180px; cursor: help;" title="{{ $vehicle->reason }}">
                                    {{ $vehicle->reason }}
                                </span>
                            </td>
                            <td>{{ $vehicle->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($vehicle->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($vehicle->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    {{-- Approve / Reject --}}
                                    @if($vehicle->status === 'pending')
                                        <form action="{{ route('admin.vehicles.approve', $vehicle) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.vehicles.reject', $vehicle) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                        </form>
                                    @endif

                                    {{-- Edit Button --}}
                                    <button
                                        class="btn btn-sm btn-outline-warning"
                                        onclick="openEditModal(
                                            {{ $vehicle->id }},
                                            '{{ $vehicle->plate_no }}',
                                            '{{ $vehicle->type }}',
                                            '{{ $vehicle->status }}',
                                            '{{ addslashes($vehicle->reason) }}'
                                        )"
                                    >Edit</button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" class="text-center text-muted py-4" style="display:none;">
                No vehicles match your search criteria.
            </div>

        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Plate No.</label>
                        <input type="text" class="form-control text-uppercase font-monospace" name="plate_no" id="editPlate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select class="form-select" name="type" id="editType">
                            <option value="car">Car</option>
                            <option value="motorcycle">Motorcycle</option>
                            <option value="van">Van</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason</label>
                        <textarea class="form-control" name="reason" id="editReason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="editStatus">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filters
        const typeFilter   = document.getElementById('filterType');
        const statusFilter = document.getElementById('filterStatus');
        const searchInput  = document.getElementById('searchVehicle');
        const rows         = document.querySelectorAll('.vehicle-row');
        const noResults    = document.getElementById('noResults');

        function applyFilters() {
            const type   = typeFilter.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            const query  = searchInput.value.toLowerCase().trim();
            let visible  = 0;

            rows.forEach(row => {
                const matchType   = !type   || row.dataset.type === type;
                const matchStatus = !status || row.dataset.status === status;
                
                const plateVal = row.dataset.plate || "";
                const matricVal = row.dataset.matric || "";
                const matchSearch = !query || plateVal.includes(query) || matricVal.includes(query);

                if (matchType && matchStatus && matchSearch) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visible === 0 ? 'block' : 'none';
        }

        if(typeFilter && statusFilter && searchInput) {
            typeFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        }
    });

    // Edit Modal
    function openEditModal(id, plate, type, status, reason) {
        document.getElementById('editPlate').value  = plate;
        document.getElementById('editType').value   = type;
        document.getElementById('editStatus').value = status;
        document.getElementById('editReason').value = reason;
        document.getElementById('editForm').action  = '/admin/vehicles/' + id;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    // Delete Confirmation
    function confirmDelete() {
        return confirm('Are you sure you want to delete this vehicle? This cannot be undone.');
    }
</script>

@endsection