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
                    <option value="van">Van</option>
                </select>

                <select class="form-select form-select-sm w-auto" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>

                <input type="text" class="form-control form-control-sm" id="searchPlate" placeholder="Search Plate Number..." style="max-width: 300px;">
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
                        <th>Applied Date</th>
                        <th>Status</th>
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
                            <td>{{ $vehicle->created_at->format('d M Y') }}</td>
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
                                {{-- Approve / Reject --}}
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
                                @endif

                                {{-- Edit Button --}}
                                <button
                                    class="btn btn-sm btn-warning ms-1"
                                    onclick="openEditModal(
                                        {{ $vehicle->id }},
                                        '{{ $vehicle->plate_no }}',
                                        '{{ $vehicle->type }}',
                                        '{{ $vehicle->status }}',
                                        '{{ addslashes($vehicle->reason) }}'
                                    )"
                                >Edit</button>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline ms-1" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" class="text-center text-muted py-3" style="display:none;">
                No vehicles match your search.
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
                        <label class="form-label">Plate No.</label>
                        <input type="text" class="form-control" name="plate_no" id="editPlate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" name="type" id="editType">
                            <option value="car">Car</option>
                            <option value="motorcycle">Motorcycle</option>
                            <option value="van">Van</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" id="editReason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
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
    // Filters
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