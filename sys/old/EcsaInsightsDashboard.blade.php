{{-- @extends('layouts.app') --}}

@section('styles')
    <style>
        .dashboard-card {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #206bc4;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            text-transform: uppercase;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }

        .table-targets td {
            font-size: 0.875rem;
        }
    </style>
@endsection

{{-- @section('content') --}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    ECSA Health Community Insights Dashboard
                </h2>
                <p class="text-muted mt-1">{{ $Desc }}</p>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader stat-label">Total Clusters</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2 stat-value">{{ $totalClusters }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader stat-label">Reporting Clusters</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2 stat-value">{{ $chartData['series'][0] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader stat-label">Completion Rate</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2 stat-value">
                                {{ number_format(($chartData['series'][0] / array_sum($chartData['series'])) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader stat-label">Active Reports</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2 stat-value">{{ $activeReports }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Cluster Reporting Status</h3>
                    </div>
                    <div class="card-body">
                        <div id="cluster-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Strategic Objectives Progress</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress-grid">
                            @foreach ($strategicObjectives as $objective)
                                <div class="mb-3">
                                    <div class="d-flex mb-1">
                                        <div>{{ $objective->SO_Name }}</div>
                                        <div class="ms-auto">{{ $objective->completion_rate }}%</div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $objective->completion_rate }}%"
                                            role="progressbar" aria-valuenow="{{ $objective->completion_rate }}"
                                            aria-valuemin="0" aria-valuemax="100"
                                            aria-label="{{ $objective->SO_Name }}">
                                            <span class="visually-hidden">{{ $objective->completion_rate }}%
                                                Complete</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Performance Indicators and Targets</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-targets">
                                <thead>
                                    <tr>
                                        <th>Indicator</th>
                                        <th>Baseline (2023-2024)</th>
                                        <th>Target Year 1</th>
                                        <th>Target Year 2</th>
                                        <th>Target Year 3</th>
                                        <th>Current Value</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($performanceIndicators as $indicator)
                                        <tr>
                                            <td>{{ $indicator->Indicator_Name }}</td>
                                            <td>{{ $indicator->Baseline_2023_2024 ?? 'N/A' }}</td>
                                            <td>{{ $indicator->Target_Year1 ?? 'N/A' }}</td>
                                            <td>{{ $indicator->Target_Year2 ?? 'N/A' }}</td>
                                            <td>{{ $indicator->Target_Year3 ?? 'N/A' }}</td>
                                            <td>{{ $indicator->current_value ?? 'N/A' }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar"
                                                        style="width: {{ $indicator->progress }}%" role="progressbar"
                                                        aria-valuenow="{{ $indicator->progress }}" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        aria-label="{{ $indicator->Indicator_Name }}">
                                                        <span class="visually-hidden">{{ $indicator->progress }}%
                                                            Complete</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: {!! json_encode($chartData['series']) !!},
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: {!! json_encode($chartData['labels']) !!},
                colors: ['#206bc4', '#d63939'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '50%'
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#cluster-chart"), options);
            chart.render();
        });
    </script>
@endpush
