<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Select Performance Report
                </h2>
                <div class="text-muted mt-1">Choose a report for {{ $selectedYear }} to view cluster performance
                    breakdown</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('Ecsa_CP_selectYear') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
                        <i class="fa fa-arrow-left me-2"></i>
                        Back to Year Selection
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Available Reports for {{ $selectedYear }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            @if ($reports->isEmpty())
                                <div class="alert alert-info" role="alert">
                                    <i class="fa fa-info-circle me-2"></i>
                                    No reports available for the selected year.
                                </div>
                            @else
                                @foreach ($reports as $report)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="report"
                                            id="report{{ $report->id }}" value="{{ $report->ReportingID }}">
                                        <label class="form-check-label" for="report{{ $report->id }}">
                                            {{ $report->ReportName }}
                                            <span class="text-muted d-block small">
                                                Closing Date:
                                                {{ \Carbon\Carbon::parse($report->ClosingDate)->format('d M Y') }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" id="viewReportBtn" disabled>
                            View Report
                            <i class="fa fa-chart-bar ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportInputs = document.querySelectorAll('input[name="report"]');
        const viewReportBtn = document.getElementById('viewReportBtn');

        reportInputs.forEach(input => {
            input.addEventListener('change', function() {
                viewReportBtn.disabled = false;
            });
        });

        viewReportBtn.addEventListener('click', function() {
            const selectedReport = document.querySelector('input[name="report"]:checked');
            if (selectedReport) {
                window.location.href =
                    "{{ route('Ecsa_CP_showPerformance') }}?year={{ $selectedYear }}&report=" +
                    selectedReport.value;
            }
        });
    });
</script>
