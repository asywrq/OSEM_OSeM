@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>Offence Types</strong></h1>
        <button class="btn btn-primary">+ Add Offence</button>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Offences</th>
                        <th>Amount (RM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No offence types found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection