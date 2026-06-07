@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Submit Appeal</strong></h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Compound Details</h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table table-sm mb-3">
                        <tr><th>Ref</th><td>#{{ $compound->id }}</td></tr>
                        <tr><th>Plate No.</th><td>{{ $compound->vehicle->plate_no }}</td></tr>
                        <tr><th>Offence</th><td>{{ $compound->offenceType->name }}</td></tr>
                        <tr><th>Amount</th><td>RM {{ number_format($compound->offenceType->amount, 2) }}</td></tr>
                        <tr><th>Issued</th><td>{{ $compound->issued_at->format('d M Y') }}</td></tr>
                    </table>

                    <div class="alert alert-warning">
                        You have <strong>30 days</strong> from the issue date to submit an appeal.
                        Only <strong>one appeal</strong> is allowed per compound. If approved, your
                        amount will be reduced by 50%. If rejected, the full amount must be paid
                        before end of semester examinations.
                    </div>

                    <form action="{{ route('user.appeal.store', $compound) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Reason for Appeal</label>
                            <textarea class="form-control" name="reason" rows="4"
                                      placeholder="Explain why you are appealing this compound..."
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Submit Appeal</button>
                        <a href="{{ route('user.my-compounds') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection