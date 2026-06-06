@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>All Vehicles</strong></h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Plate No.</th>
                        <th>Type</th>
                        <th>Owner</th>
                        <th>Reason</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td><code>{{ strtoupper($vehicle->plate_no) }}</code></td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td>
                                {{ $vehicle->user->name }}
                                <br>
                                <small class="text-muted">{{ $vehicle->user->email }}</small>
                            </td>
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
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection