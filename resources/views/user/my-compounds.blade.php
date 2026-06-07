@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>My Compounds</strong></h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Plate No.</th>
                        <th>Offence</th>
                        <th>Amount (RM)</th>
                        <th>Issued</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compounds as $compound)
                        <tr>
                            <td>#{{ $compound->id }}</td>
                            <td>{{ $compound->vehicle->plate_no }}</td>
                            <td>{{ $compound->offenceType->name }}</td>
                            <td>
                                @if($compound->is_discounted)
                                    <span class="text-decoration-line-through text-muted">
                                        {{ number_format($compound->offenceType->amount, 2) }}
                                    </span>
                                    {{ number_format($compound->offenceType->amount * 0.5, 2) }}
                                    <span class="badge bg-success">50% off</span>
                                @else
                                    {{ number_format($compound->offenceType->amount, 2) }}
                                @endif
                            </td>
                            <td>{{ $compound->issued_at->format('d M Y') }}</td>
                            <td>
                                @php
                                    $badge = match($compound->status) {
                                        'paid'      => 'secondary',
                                        'resolved'  => 'success',
                                        'appealing' => 'info',
                                        default     => 'danger',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($compound->status) }}</span>
                            </td>
                            <td>
                                @if($compound->status === 'unpaid' && !$compound->appeal)
                                    <a href="{{ route('user.appeal.show', $compound) }}"
                                       class="btn btn-sm btn-warning">
                                        Appeal
                                    </a>
                                @elseif($compound->appeal)
                                    <span class="text-muted" style="font-size:0.85rem;">
                                        Appeal {{ ucfirst($compound->appeal->result) }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No compounds found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection