<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Cluster Performance Breakdown
                </h2>
                <div class="text-muted mt-1">{{ $report->ReportName }} - {{ $selectedYear }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('Ecsa_CP_selectReport', ['year' => $selectedYear]) }}"
                        class="btn btn-outline-primary d-none d-sm-inline-block">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back to Report Selection
                    </a>
                    <a href="{{ route('Ecsa_CP_exportCsv', ['year' => $selectedYear, 'report' => $selectedReport]) }}"
                        class="btn btn-primary d-none d-sm-inline-block">
                        <i class="fa fa-file-export me-2"></i>
                        Export to CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="card-title">Report Overview</h3>
                            <div class="ms-auto">
                                <span
                                    class="badge bg-{{ $report->status === 'Completed' ? 'success' : 'warning' }} text-dark">
                                    {{ $report->status }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <span class="text-muted">Report Name:</span>
                                    <strong>{{ $report->ReportName }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <span class="text-muted">Year:</span>
                                    <strong>{{ $report->Year }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <span class="text-muted">Closing Date:</span>
                                    <strong>{{ \Carbon\Carbon::parse($report->ClosingDate)->format('d M Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Overall Performance Distribution</h3>
                        <div id="overallPerformanceChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Top Performing Clusters</h3>
                        <div id="topPerformingClustersChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($performanceData as $clusterId => $clusterData)
            <div class="card mt-4">
                <div class="card-header" id="cluster-header-{{ $clusterId }}">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#cluster-collapse-{{ $clusterId }}"
                            aria-expanded="false" aria-controls="cluster-collapse-{{ $clusterId }}">
                            {{ $clusterData['clusterName'] }}
                            <span class="badge bg-primary text-white ms-2">{{ $clusterData['totalIndicators'] }}
                                Indicators</span>
                        </button>
                    </h2>
                </div>

                <div id="cluster-collapse-{{ $clusterId }}" class="collapse"
                    aria-labelledby="cluster-header-{{ $clusterId }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <h4 class="card-title">Performance Breakdown</h4>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $clusterData['overallMetPercentage'] }}%"
                                            role="progressbar"
                                            aria-valuenow="{{ $clusterData['overallMetPercentage'] }}"
                                            aria-valuemin="0" aria-valuemax="100"
                                            title="Met: {{ $clusterData['overallMetPercentage'] }}%"></div>
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $clusterData['overallProgressingPercentage'] }}%"
                                            role="progressbar"
                                            aria-valuenow="{{ $clusterData['overallProgressingPercentage'] }}"
                                            aria-valuemin="0" aria-valuemax="100"
                                            title="Progressing: {{ $clusterData['overallProgressingPercentage'] }}%">
                                        </div>
                                        <div class="progress-bar bg-danger text-light"
                                            style="width: {{ $clusterData['overallNotPerformingPercentage'] }}%"
                                            role="progressbar"
                                            aria-valuenow="{{ $clusterData['overallNotPerformingPercentage'] }}"
                                            aria-valuemin="0" aria-valuemax="100"
                                            title="Not Performing: {{ $clusterData['overallNotPerformingPercentage'] }}%">
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex justify-content-between">
                                        <small class="text-success text-dark">Met:
                                            {{ $clusterData['overallMetPercentage'] }}%</small>
                                        <small class="text-warning text-dark">Progressing:
                                            {{ $clusterData['overallProgressingPercentage'] }}%</small>
                                        <small class="text-danger text-light">Not Performing:
                                            {{ $clusterData['overallNotPerformingPercentage'] }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4>Indicator Details</h4>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Indicator</th>
                                            <th>Baseline</th>
                                            <th>Target</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clusterData['indicators'] as $indicator)
                                            <tr>
                                                <td>{{ $indicator['name'] }}</td>
                                                <td>{{ $indicator['baseline'] ?? 'N/A' }}</td>
                                                <td>{{ $indicator['target'] ?? 'N/A' }}</td>
                                                <td>{{ $indicator['score'] ?? 'N/A' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $indicator['status'] === 'met' ? 'success' : ($indicator['status'] === 'progressing' ? 'warning' : 'danger') }} text-light">
                                                        {{ ucfirst($indicator['status']) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (!empty($clusterData['missingReports']))
                            <div class="mt-4">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#missingReportsModal-{{ $clusterId }}">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    View Missing Reports
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (!empty($clusterData['missingReports']))
                <div class="modal fade" id="missingReportsModal-{{ $clusterId }}" tabindex="-1" role="dialog"
                    aria-labelledby="missingReportsModalLabel-{{ $clusterId }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title text-dark" id="missingReportsModalLabel-{{ $clusterId }}">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    Attention Required: Missing Reports for {{ $clusterData['clusterName'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="missing-reports-container">
                                    @foreach ($clusterData['missingReports'] as $index => $missingReport)
                                        <div class="missing-report-card mb-4 {{ $index % 2 == 0 ? 'bg-light' : '' }}">
                                            <h6 class="missing-report-indicator">{{ $missingReport['indicator'] }}
                                            </h6>
                                            <div class="missing-clusters">
                                                <strong>Missing Clusters:</strong>
                                                <div class="cluster-tags mt-2">
                                                    @foreach ($missingReport['missingClusters'] as $cluster)
                                                        <span
                                                            class="badge bg-secondary text-white me-2 mb-2">{{ $cluster }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-primary">Take Action</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Prepare data for charts
        var overallPerformance = {
            met: 0,
            progressing: 0,
            notPerforming: 0
        };

        var clusterPerformance = [];

        @foreach ($performanceData as $clusterId => $clusterData)
            overallPerformance.met += {{ $clusterData['metCount'] }};
            overallPerformance.progressing += {{ $clusterData['progressingCount'] }};
            overallPerformance.notPerforming += {{ $clusterData['notPerformingCount'] }};

            clusterPerformance.push({
                cluster: '{{ $clusterData['clusterName'] }}',
                performance: {{ $clusterData['overallMetPercentage'] }}
            });
        @endforeach

        // Sort cluster performance
        clusterPerformance.sort((a, b) => b.performance - a.performance);

        // Overall Performance Distribution Chart
        var overallPerformanceChart = new ApexCharts(document.querySelector("#overallPerformanceChart"), {
            series: [overallPerformance.met, overallPerformance.progressing, overallPerformance
                .notPerforming
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Met', 'Progressing', 'Not Performing'],
            colors: ['#4CAF50', '#FFC107', '#F44336'],
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
            }],
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + " indicators"
                    }
                }
            }
        });
        overallPerformanceChart.render();

        // Top Performing Clusters Chart
        var topPerformingClustersChart = new ApexCharts(document.querySelector("#topPerformingClustersChart"), {
            series: [{
                data: clusterPerformance.slice(0, 5).map(cp => cp.performance)
            }],
            chart: {
                type: 'bar',
                height: 300
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            xaxis: {
                categories: clusterPerformance.slice(0, 5).map(cp => cp.cluster),
                labels: {
                    formatter: function(val) {
                        return val + "%"
                    }
                }
            },
            colors: ['#2196F3'],
            title: {
                text: 'Top 5 Performing Clusters',
                align: 'center',
                style: {
                    fontSize: '14px'
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + "%"
                    }
                }
            }
        });
        topPerformingClustersChart.render();
    });
</script>

<style>
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .progress {
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-weight: 500;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    }

    .missing-report-card {
        padding: 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .missing-report-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .missing-report-indicator {
        color: #d32f2f;
        margin-bottom: 10px;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .cluster-tags {
        display: flex;
        flex-wrap: wrap;
    }

    .modal-header {
        border-radius: 0.3rem 0.3rem 0 0;
    }

    .btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        color: #212529;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .text-dark {
        color: #343a40 !important;
    }
</style>
