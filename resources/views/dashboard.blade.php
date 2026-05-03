@extends('master.layout')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Dashboard</strong></h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Welcome back, {{ auth()->user()->name }}. You are logged in as <strong>{{ auth()->user()->role }}</strong>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection