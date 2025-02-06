<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Strategic Objective Performance Overview
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('EcsaReportIndex') }}" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Reporting Period</label>
                                <select name="reporting_period" class="form-select" onchange="this.form.submit()">
                                    @foreach (['Quarterly', 'Bi-Annual', 'Annual'] as $period)
                                        <option value="{{ $period }}"
                                            {{ $reportingPeriod == $period ? 'selected' : '' }}>
                                            {{ $period }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Clusters</label>
                                <select name="clusters[]" class="form-select" multiple onchange="this.form.submit()">
                                    @foreach ($clusters as $cluster)
                                        <option value="{{ $cluster->ClusterID }}"
                                            {{ in_array($cluster->ClusterID, $selectedClusters) ? 'selected' : '' }}>
                                            {{ $cluster->Cluster_Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Indicator Type</label>
                                <select name="indicator_type" class="form-select" onchange="this.form.submit()">
                                    <option value="all" {{ $indicatorType == 'all' ? 'selected' : '' }}>All</option>
                                    @foreach ($indicatorTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ $indicatorType == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Strategic Objectives</div>
                            </div>
                            <div class="h1 mb-3">{{ count($performanceData) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total Indicators</div>
                            </div>
                            <div class="h1 mb-3">{{ $performanceData->sum('indicatorCount') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Performing Indicators</div>
                            </div>
                            <div class="h1 mb-3">{{ $performanceData->sum('performingCount') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Non-Performing Indicators</div>
                            </div>
                            <div class="h1 mb-3">{{ $performanceData->sum('nonPerformingCount') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Performance Overview</h3>
                        </div>
                        <div class="card-body">
                            <div id="performance-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($performanceData as $objectiveId => $data)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data['name'] }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>{{ $data['description'] }}</p>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Performance</span>
                                        <span>{{ $data['performancePercentage'] }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $data['performancePercentage'] }}%"
                                            role="progressbar" aria-valuenow="{{ $data['performancePercentage'] }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">{{ $data['performancePercentage'] }}%
                                                Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body p-2 text-center">
                                                <div class="h1 m-0">{{ $data['indicatorCount'] }}</div>
                                                <div class="text-muted mb-3">Total Indicators</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body p-2 text-center">
                                                <div class="h1 m-0">{{ $data['performingCount'] }}</div>
                                                <div class="text-muted mb-3">Performing</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body p-2 text-center">
                                                <div class="h1 m-0">{{ $data['nonPerformingCount'] }}</div>
                                                <div class="text-muted mb-3">Non-Performing</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-body p-2 text-center">
                                                <div class="h1 m-0">
                                                    {{ $data['totalReported'] }}/{{ $data['totalTarget'] }}</div>
                                                <div class="text-muted mb-3">Reported/Target</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4>Indicators</h4>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Number</th>
                                            <th>Name</th>
                                            <th>Response Type</th>
                                            <th>Reported Value</th>
                                            <th>Target Value</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['indicators'] as $indicator)
                                            <tr>
                                                <td>{{ $indicator['number'] }}</td>
                                                <td>{{ $indicator['name'] }}</td>
                                                <td>{{ $indicator['responseType'] }}</td>
                                                <td>{{ $indicator['reportedValue'] }}</td>
                                                <td>{{ $indicator['targetValue'] }}</td>
                                                <td>
                                                    @if ($indicator['isPerforming'])
                                                        <span class="badge bg-success">Performing</span>
                                                    @else
                                                        <span class="badge bg-danger">Non-Performing</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('EcsaReportViewIndicatorDetails', ['strategic_objective_id' => $objectiveId, 'indicator_id' => $indicator['id']]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                <a href="{{ route('EcsaReportExportPerformanceReport', request()->query()) }}"
                    class="btn btn-primary">
                    Export Performance Report
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var performanceData = @json($performanceData);
        var chartData = Object.values(performanceData).map(function(data) {
            return {
                x: data.name,
                y: data.performancePercentage
            };
        });

        var options = {
            series: [{
                name: 'Performance',
                data: chartData
            }],
            chart: {
                height: 300,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val + "%";
                },
                offsetX: 10
            },
            xaxis: {
                type: 'category',
                labels: {
                    formatter: function(val) {
                        return val + "%";
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Strategic Objectives'
                }
            },
            colors: ['#206bc4'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + "%";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#performance-chart"), options);
        chart.render();
    });
</script>
