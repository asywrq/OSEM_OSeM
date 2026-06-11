@extends('master.layout')

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <h1 class="h3 mb-4"><strong>Dashboard</strong></h1>

    {{-- Row 2: Stat Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Compounds This Month</p>
                            <h2 class="mb-0 fw-bold">{{ $compoundsThisMonth }}</h2>
                        </div>
                        <div class="p-2 rounded" style="background: rgba(23,160,96,0.1);">
                            <i data-feather="file-text" style="color: #17a060; width:20px; height:20px;"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Last 30 days</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Compounds Today</p>
                            <h2 class="mb-0 fw-bold">{{ $compoundsToday }}</h2>
                        </div>
                        <div class="p-2 rounded" style="background: rgba(23,160,96,0.1);">
                            <i data-feather="calendar" style="color: #17a060; width:20px; height:20px;"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Unresolved Appeals</p>
                            <h2 class="mb-0 fw-bold">{{ $appealsUnresolved }}</h2>
                        </div>
                        <div class="p-2 rounded" style="background: rgba(255,193,7,0.1);">
                            <i data-feather="alert-circle" style="color: #ffc107; width:20px; height:20px;"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Pending review</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Unresolved Applications</p>
                            <h2 class="mb-0 fw-bold">{{ $stickerUnresolved }}</h2>
                        </div>
                        <div class="p-2 rounded" style="background: rgba(255,193,7,0.1);">
                            <i data-feather="truck" style="color: #ffc107; width:20px; height:20px;"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Sticker applications</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Payment Received</p>
                            <h2 class="mb-0 fw-bold">RM {{ number_format($paymentThisMonth, 2) }}</h2>
                        </div>
                        <div class="p-2 rounded" style="background: rgba(23,160,96,0.1);">
                            <i data-feather="dollar-sign" style="color: #17a060; width:20px; height:20px;"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Last 30 days</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Row 3 --}}
    <div class="row g-3">

        {{-- Top 5 Officers --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Officers</h5>
                    <p class="text-muted small mb-0">By total activity to date</p>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Officer</th>
                                <th class="text-end pe-3">Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topOfficers as $i => $officer)
                            <tr>
                                <td class="ps-3">
                                    @if($i === 0)
                                        <span style="color: #ffc107;">①</span>
                                    @elseif($i === 1)
                                        <span style="color: #adb5bd;">②</span>
                                    @elseif($i === 2)
                                        <span style="color: #cd7f32;">③</span>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>{{ $officer->name }}</td>
                                <td class="text-end pe-3">
                                    <span class="badge" style="background: rgba(23,160,96,0.15); color: #17a060;">
                                        {{ $officer->total_activity }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No activity yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Weekly Officer Activity Chart --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Weekly Officer Activity</h5>
                    <p class="text-muted small mb-0">Current week — Mon to Sun</p>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Weekly Compounds Issued Chart --}}
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Weekly Compounds Issued</h5>
                    <p class="text-muted small mb-0">Current week — Mon to Sun</p>
                </div>
                <div class="card-body">
                    <canvas id="compoundsChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var labels   = @json($weekLabels);
    var activity = @json($weeklyActivity);
    var compounds = @json($weeklyCompounds);

    // Activity chart
    new Chart(document.getElementById('activityChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Activity',
                data: activity,
                backgroundColor: 'rgba(23,160,96,0.7)',
                borderColor: '#17a060',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Compounds chart
    new Chart(document.getElementById('compoundsChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Compounds',
                data: compounds,
                backgroundColor: 'rgba(23,160,96,0.1)',
                borderColor: '#17a060',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#17a060',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

});
</script>
@endsection