<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="display-4 text-center mb-3">Select a Report</h1>
            <div class="d-flex justify-content-center align-items-center mb-5">
                <span class="badge bg-blue-lt fs-5 me-2">
                    <i class="fas fa-layer-group me-1"></i>
                    {{ $selectedCluster === 'All clusters' ? 'All Clusters' : $clusters->firstWhere('ClusterID', $selectedCluster)->Cluster_Name }}
                </span>
                <i class="fas fa-chevron-right text-muted mx-2"></i>
                <span class="badge bg-green-lt fs-5">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ $selectedYear }}
                </span>
            </div>

            <div class="row g-4">
                @foreach ($reports as $report)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card card-stacked-hover h-100 cursor-pointer report-card"
                            data-report="{{ $report->ReportingID }}">
                            <div
                                class="card-status-top bg-{{ $report->status === 'Completed' ? 'success' : ($report->status === 'In Progress' ? 'warning' : 'danger') }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-file-alt me-2 text-muted"></i>
                                        {{ $report->ReportName }}
                                    </h3>
                                    <span
                                        class="badge bg-{{ $report->status === 'Completed' ? 'success' : ($report->status === 'In Progress' ? 'warning' : 'danger') }}-lt">
                                        {{ $report->status }}
                                    </span>
                                </div>
                                <p class="text-muted flex-grow-1">{{ Str::limit($report->Description, 100) }}</p>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Closing Date:
                                        </span>
                                        <span class="badge bg-blue-lt">
                                            {{ \Carbon\Carbon::parse($report->ClosingDate)->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="fas fa-chart-pie me-1"></i>
                                            Type:
                                        </span>
                                        <span class="badge bg-purple-lt">
                                            {{ $report->Type }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-{{ $report->status === 'Completed' ? 'success' : ($report->status === 'In Progress' ? 'warning' : 'danger') }}"
                                        role="progressbar"
                                        style="width: {{ $report->status === 'Completed' ? '100' : ($report->status === 'In Progress' ? '50' : '0') }}%"
                                        aria-valuenow="{{ $report->status === 'Completed' ? '100' : ($report->status === 'In Progress' ? '50' : '0') }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<form id="report-form" action="{{ route('performance-overview') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="cluster" value="{{ $selectedCluster }}">
    <input type="hidden" name="year" value="{{ $selectedYear }}">
    <input type="hidden" name="report" id="selected-report">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportCards = document.querySelectorAll('.report-card');
        const reportForm = document.getElementById('report-form');
        const selectedReportInput = document.getElementById('selected-report');

        reportCards.forEach(card => {
            card.addEventListener('click', function() {
                const report = this.dataset.report;
                selectedReportInput.value = report;
                reportForm.submit();
            });

            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<style>
    .card-stacked-hover {
        transition: all 0.3s ease;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .report-card:hover .card-title {
        color: #206bc4;
    }

    .card-status-top {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
        border-radius: 3px 3px 0 0;
    }
</style>
