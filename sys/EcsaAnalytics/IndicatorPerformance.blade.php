<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Indicator Performance Overview</h1>
                <a href="{{ route('export-csv', ['cluster' => $selectedCluster, 'year' => $selectedYear, 'report' => $selectedReport]) }}"
                    class="btn btn-outline-primary">
                    <i class="fas fa-file-csv me-2"></i>Export CSV
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                            <div class="h5 mb-1">Cluster</div>
                            <span class="badge bg-blue-lt fs-5">
                                <i class="fas fa-layer-group me-1"></i>
                                {{ $selectedCluster === 'All clusters' ? 'All Clusters' : $clusters->firstWhere('ClusterID', $selectedCluster)->Cluster_Name }}
                            </span>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="h5 mb-1">Year</div>
                            <span class="badge bg-green-lt fs-5">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $selectedYear }}
                            </span>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="h5 mb-1">Report</div>
                            <span class="badge bg-purple-lt fs-5">
                                <i class="fas fa-file-alt me-1"></i>
                                {{ $report->ReportName }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">Overall Performance Summary</h3>
                    <div class="row g-3">
                        @php
                            $totalIndicators = 0;
                            $metCount = 0;
                            $progressingCount = 0;
                            $notPerformingCount = 0;
                            foreach ($performanceData as $objective) {
                                foreach ($objective['indicators'] as $indicator) {
                                    $totalIndicators++;
                                    if ($indicator['status'] === 'met') {
                                        $metCount++;
                                    } elseif ($indicator['status'] === 'progressing') {
                                        $progressingCount++;
                                    } elseif ($indicator['status'] === 'not performing') {
                                        $notPerformingCount++;
                                    }
                                }
                            }
                        @endphp
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-primary text-white avatar">
                                                <i class="fas fa-chart-line"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $totalIndicators }} Indicators
                                            </div>
                                            <div class="text-muted">
                                                Total
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-green text-white avatar">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $metCount }} Met
                                            </div>
                                            <div class="text-muted">
                                                {{ number_format(($metCount / $totalIndicators) * 100, 1) }}% of total
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-yellow text-white avatar">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $progressingCount }} Progressing
                                            </div>
                                            <div class="text-muted">
                                                {{ number_format(($progressingCount / $totalIndicators) * 100, 1) }}% of
                                                total
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-red text-white avatar">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $notPerformingCount }} Not Performing
                                            </div>
                                            <div class="text-muted">
                                                {{ number_format(($notPerformingCount / $totalIndicators) * 100, 1) }}%
                                                of total
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($performanceData as $objectiveId => $objective)
                <div class="card mb-4">
                    <div
                        class="card-status-start bg-{{ $objective['status'] === 'met' ? 'green' : ($objective['status'] === 'progressing' ? 'yellow' : 'red') }}">
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $objective['name'] }}
                            <span
                                class="badge bg-{{ $objective['status'] === 'met' ? 'green' : ($objective['status'] === 'progressing' ? 'yellow' : 'red') }}-lt ms-2">
                                {{ ucfirst($objective['status']) }}
                            </span>
                        </h3>
                        <div class="card-actions">
                            <a href="#" class="btn btn-icon" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $objectiveId }}">
                                <i class="fas fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body collapse show" id="collapse-{{ $objectiveId }}">
                        <p class="text-muted mb-4">{{ $objective['description'] }}</p>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Indicator</th>
                                        <th>Baseline</th>
                                        <th>Target</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($objective['indicators'] as $index => $indicator)
                                        <tr>
                                            <td>{{ $indicator['name'] }}</td>
                                            <td>{{ $indicator['baseline'] ?? 'N/A' }}</td>
                                            <td>{{ $indicator['target'] ?? 'N/A' }}</td>
                                            <td>{{ $indicator['score'] }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $indicator['status'] === 'met' ? 'green' : ($indicator['status'] === 'progressing' ? 'yellow' : 'red') }}-lt">
                                                    {{ ucfirst($indicator['status']) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal-{{ $objectiveId }}-{{ $index }}">
                                                    <i class="fas fa-info-circle me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach ($objective['indicators'] as $index => $indicator)
                    <div class="modal modal-blur fade" id="modal-{{ $objectiveId }}-{{ $index }}"
                        tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $indicator['name'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <div class="text-muted">Baseline</div>
                                            <div class="h3">{{ $indicator['baseline'] ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Target</div>
                                            <div class="h3">{{ $indicator['target'] ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-muted">Score</div>
                                            <div class="h3">{{ $indicator['score'] }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Status</div>
                                        <div class="h3">
                                            <span
                                                class="badge bg-{{ $indicator['status'] === 'met' ? 'green' : ($indicator['status'] === 'progressing' ? 'yellow' : 'red') }}-lt">
                                                {{ ucfirst($indicator['status']) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Responsible Clusters</div>
                                        <div>
                                            @foreach ($indicator['responsibleClusters'] as $cluster)
                                                <span class="badge bg-blue-lt me-1">{{ $cluster }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted mb-1">Cluster Responses</div>
                                        <ul class="list-group">
                                            @foreach ($indicator['clusterResponses'] as $clusterId => $response)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $clusters->firstWhere('ClusterID', $clusterId)->Cluster_Name }}</strong>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $response['ReportingComment'] }}</small>
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ $indicator['responseType'] === 'Boolean' || $indicator['responseType'] === 'Yes/No'
                                                            ? ($response['Response']
                                                                ? 'Yes'
                                                                : 'No')
                                                            : $response['Response'] }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Add event listeners for collapse toggles
        var collapseToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
        collapseToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                var icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });
    });
</script>

<style>
    .card-status-start {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
    }

    .avatar {
        width: 3rem;
        height: 3rem;
        line-height: 3rem;
        font-size: 1.2rem;
    }

    .modal-header,
    .modal-footer {
        background-color: #f8fafc;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8fafc;
    }
</style>
