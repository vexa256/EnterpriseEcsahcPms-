<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Strategic Objectives Performance Overview</h2>
                <div class="text-muted mt-1">{{ $report->ReportName }} - {{ $selectedYear }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('Ecsa_SO_selectReport', ['year' => $selectedYear]) }}"
                        class="btn btn-outline-primary d-none d-sm-inline-block">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back to Report Selection
                    </a>
                    <a href="{{ route('Ecsa_SO_exportCsv', ['year' => $selectedYear, 'report' => $selectedReport]) }}"
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
        <div class="row row-cards">
            @foreach ($performanceData as $objectiveId => $objective)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $objective['name'] }}</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $objective['description'] }}</p>
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span
                                        class="status-indicator status-{{ $objective['allTargetsMet'] ? 'success' : ($objective['fullyReported'] ? 'warning' : 'danger') }}"></span>
                                    <span
                                        class="ms-2">{{ $objective['allTargetsMet'] ? 'All Targets Met' : ($objective['fullyReported'] ? 'In Progress' : 'Incomplete Reporting') }}</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $objective['allTargetsMet'] ? 'success' : ($objective['fullyReported'] ? 'warning' : 'danger') }}"
                                        style="width: {{ $objective['allTargetsMet'] ? '100' : ($objective['fullyReported'] ? '50' : '25') }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center">
                                <span class="text-muted">{{ count($objective['indicators']) }} Indicators</span>
                                <a href="#" class="btn btn-link ms-auto" data-bs-toggle="modal"
                                    data-bs-target="#modal-objective-{{ $objectiveId }}">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@foreach ($performanceData as $objectiveId => $objective)
    <div class="modal modal-blur fade" id="modal-objective-{{ $objectiveId }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $objective['name'] }} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
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
                                @foreach ($objective['indicators'] as $indicator)
                                    <tr>
                                        <td>{{ $indicator['name'] }}</td>
                                        <td>{{ $indicator['baseline'] ?? 'N/A' }}</td>
                                        <td>{{ $indicator['target'] ?? 'N/A' }}</td>
                                        <td>{{ $indicator['score'] ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="status-pill status-{{ $indicator['status'] === 'met' ? 'success' : ($indicator['status'] === 'progressing' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($indicator['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (!empty($objective['missingReports']))
                        <div class="missing-reports-container mt-4">
                            <h4 class="missing-reports-title">
                                <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                                Missing Reports
                            </h4>
                            <div class="missing-reports-grid">
                                @foreach ($objective['missingReports'] as $missingReport)
                                    <div class="missing-report-card">
                                        <h5 class="missing-report-indicator">{{ $missingReport['indicator'] }}</h5>
                                        <div class="missing-clusters">
                                            @foreach ($missingReport['missingClusters'] as $cluster)
                                                <span class="missing-cluster-badge">{{ $cluster }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-success {
        background-color: #2fb344;
    }

    .status-warning {
        background-color: #f59f00;
    }

    .status-danger {
        background-color: #d63939;
    }

    .status-pill {
        padding: 0.25em 0.6em;
        border-radius: 10rem;
        font-size: 0.75em;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pill.status-success {
        background-color: #ecfdf3;
        color: #2fb344;
    }

    .status-pill.status-warning {
        background-color: #fff8e6;
        color: #f59f00;
    }

    .status-pill.status-danger {
        background-color: #fae9e9;
        color: #d63939;
    }

    .missing-reports-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .missing-reports-title {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: #495057;
    }

    .missing-reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .missing-report-card {
        background-color: white;
        border-radius: 6px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .missing-report-indicator {
        font-size: 1rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .missing-clusters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .missing-cluster-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 0.25em 0.5em;
        border-radius: 4px;
        font-size: 0.75em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function() {
                var modalBody = this.querySelector('.modal-body');
                var table = modalBody.querySelector('table');
                if (table.offsetHeight > modalBody.offsetHeight) {
                    modalBody.style.maxHeight = '70vh';
                    modalBody.style.overflowY = 'auto';
                }
            });
        });
    });
</script>
