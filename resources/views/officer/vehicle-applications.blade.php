@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Vehicle Applications</strong></h1>

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
                <span class="text-muted fw-semibold me-1">Pending Applications</span>
                <input type="text" class="form-control form-control-sm" id="searchPlate" placeholder="Search plate number..." style="max-width: 300px;">
                <select class="form-select form-select-sm w-auto ms-auto" id="filterType">
                    <option value="">All Types</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                </select>
            </div>

            {{-- Table --}}
            <table class="table table-hover align-middle" id="applicationsTable">
                <thead>
                    <tr>
                        <th>Plate No.</th>
                        <th>Applicant</th>
                        <th>Matric/Staff No.</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr
                            data-type="{{ $vehicle->type }}"
                            data-plate="{{ strtolower($vehicle->plate_no) }}"
                        >
                            <td><code>{{ strtoupper($vehicle->plate_no) }}</code></td>
                            <td>{{ $vehicle->user->name }}</td>
                            <td>{{ $vehicle->user->matric_no ?? $vehicle->user->staff_no ?? '—' }}</td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td>{{ $vehicle->reason }}</td>
                            <td>
                                <form action="{{ route('officer.vehicle-applications.approve', $vehicle) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                </form>
                                <form action="{{ route('officer.vehicle-applications.reject', $vehicle) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No pending applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" class="text-center text-muted py-3" style="display:none;">
                No applications match your search.
            </div>

        </div>
    </div>
</div>

<script>
    const searchInput  = document.getElementById('searchPlate');
    const typeFilter   = document.getElementById('filterType');
    const rows         = document.querySelectorAll('#applicationsTable tbody tr[data-plate]');
    const noResults    = document.getElementById('noResults');

    function applyFilters() {
        const plate  = searchInput.value.toLowerCase();
        const type   = typeFilter.value.toLowerCase();
        let visible  = 0;

        rows.forEach(row => {
            const matchPlate = !plate || row.dataset.plate.includes(plate);
            const matchType  = !type  || row.dataset.type === type;

            if (matchPlate && matchType) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', applyFilters);
    typeFilter.addEventListener('change', applyFilters);
</script>

@endsection