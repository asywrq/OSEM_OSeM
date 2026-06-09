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

    <div class="card mb-3">
        <div class="card-body">
            {{-- Filters Row --}}
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <span class="text-muted fw-semibold me-2">Pending Applications</span>
                <select class="form-select form-select-sm w-auto" id="filterType">
                    <option value="">All Types</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                </select>
                <input type="text" class="form-control form-control-sm" id="searchPlate" placeholder="Search plate or matric no..." style="max-width: 300px;">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            {{-- Table --}}
            <table class="table table-hover align-middle mb-0" id="applicationsTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Plate No.</th>
                        <th>Applicant</th>
                        <th>Matric/Staff No.</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr
                            data-type="{{ $vehicle->type }}"
                            data-plate="{{ strtolower($vehicle->plate_no) }}"
                            data-matric="{{ strtolower($vehicle->user->matric_or_staff_no ?? '') }}"
                        >
                            <td class="ps-4"><span class="fw-bold font-monospace text-dark">{{ strtoupper($vehicle->plate_no) }}</span></td>
                            <td>{{ $vehicle->user->name }}</td>
                            <td>
                                @if($vehicle->user->matric_or_staff_no)
                                    {{ $vehicle->user->matric_or_staff_no }}
                                @else
                                    <span class="badge bg-secondary">External</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td class="text-muted">{{ $vehicle->reason }}</td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('officer.vehicle-applications.approve', $vehicle) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success">Accept</button>
                                </form>
                                <form action="{{ route('officer.vehicle-applications.reject', $vehicle) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No pending applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="noResults" class="text-center text-muted py-4" style="display:none;">
                No applications match your search criteria.
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
        const query  = searchInput.value.toLowerCase().trim();
        const type   = typeFilter.value.toLowerCase();
        let visible  = 0;

        rows.forEach(row => {
            const plateVal = row.dataset.plate || "";
            const matricVal = row.dataset.matric || "";
            
            // Check if query matches either plate OR matric number
            const matchSearch = !query || plateVal.includes(query) || matricVal.includes(query);
            const matchType   = !type  || row.dataset.type === type;

            if (matchSearch && matchType) {
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