@extends('master.layout')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>My Vehicle</strong></h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal">
            + Apply for Sticker
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="alert alert-info">
        Vehicle sticker applications are reviewed by security officers. You must reapply each semester with your current vehicle information.
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Plate No.</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Applied</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->plate_no }}</td>
                            <td>{{ ucfirst($vehicle->type) }}</td>
                            <td>{{ $vehicle->reason }}</td>
                            <td>{{ $vehicle->created_at->format('d M Y') }}</td>
                            <td>
                                @php
                                    $badge = match($vehicle->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default    => 'warning',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($vehicle->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No vehicle application found. Click "Apply for Sticker" to register your vehicle.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Apply for Sticker Modal --}}
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.my-vehicle.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Apply for Vehicle Sticker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plate No.</label>
                        <input type="text" class="form-control" name="plate_no"
                               placeholder="e.g. WXY1234" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle Type</label>
                        <select class="form-select" name="type" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="car">Car</option>
                            <option value="motorcycle">Motorcycle</option>
                            <option value="van">Van</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Application</label>
                        <textarea class="form-control" name="reason" rows="3"
                                  placeholder="e.g. Daily commute to campus" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection