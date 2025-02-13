{{-- Final Premium UI/UX Blade View with Enhanced Header Table --}}
<style>
    :root {
        --primary-color: #0078d4;
        --secondary-color: #50e6ff;
        --accent-color: #00b294;
        --background-color: #f0f2f5;
        --card-background: #ffffff;
        --text-primary: #252733;
        --text-secondary: #6c757d;
        --border-color: #e0e0e0;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-primary);
    }

    .card {
        background-color: var(--card-background);
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .card-title {
        color: var(--primary-color);
        font-weight: 600;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #005a9e;
        border-color: #005a9e;
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    .progress-bar {
        background-color: var(--accent-color);
    }

    .search-input {
        border-radius: 20px;
        padding-left: 40px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-bottom: none;
        padding: 2rem 2rem 1rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 2rem 2rem;
    }

    .table th {
        font-weight: 600;
        color: var(--text-secondary);
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .chart-container {
        position: relative;
        margin: auto;
        height: 300px;
        width: 100%;
    }

    .indicator-card {
        cursor: pointer;
    }

    .indicator-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .indicator-status {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
    }

    .status-completed {
        background-color: #e6f7ff;
        color: #0078d4;
    }

    .status-pending {
        background-color: #fff4e5;
        color: #ff8c00;
    }

    .details-table th {
        width: 30%;
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--text-secondary);
        font-weight: 500;
        padding: 1rem 1.5rem;
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
    }

    .stat-card {
        border-radius: 15px;
        overflow: hidden;
    }

    .stat-card-body {
        padding: 1.5rem;
    }

    .stat-card-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    .stat-card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .card-subtitle {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 0.9rem;
    }

    .collapsed {
        transition: height 0.3s ease-out;
    }
</style>

{{-- SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<!-- Current Report Summary Table -->
<div class="container-fluid px-4 py-3">
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="card-title">
                        Current Reporting Summary
                        <small>(Metrics apply exclusively to the selected report and year)</small>
                    </h2>

                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Entity</th>
                                <th>Report Name</th>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $entity->Entity }}</td>
                                <td>{{ $timeline->ReportName }}</td>
                                <td>{{ $timeline->Year }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 py-5">
    <!-- Stat Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white">
                <div class="stat-card-body d-flex align-items-center">
                    <div class="stat-card-icon bg-white text-primary me-3">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h6 class="stat-card-title text-white-50 mb-1">Total Indicators</h6>
                        <h2 class="stat-card-value mb-0">{{ $totalIndicators }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white">
                <div class="stat-card-body d-flex align-items-center">
                    <div class="stat-card-icon bg-white text-success me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="stat-card-title text-white-50 mb-1">Reported Indicators</h6>
                        <h2 class="stat-card-value mb-0">{{ $reportedIndicators }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white">
                <div class="stat-card-body d-flex align-items-center">
                    <div class="stat-card-icon bg-white text-warning me-3">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h6 class="stat-card-title text-white-50 mb-1">Remaining Indicators</h6>
                        <h2 class="stat-card-value mb-0">{{ $totalIndicators - $reportedIndicators }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info text-white">
                <div class="stat-card-body d-flex align-items-center">
                    <div class="stat-card-icon bg-white text-info me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="stat-card-title text-white-50 mb-1">Overall Progress</h6>
                        <h2 class="stat-card-value mb-0">{{ number_format($progress, 2) }}%</h2>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progress }}%;"
                        aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-5">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reporting Progress</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export
                                    Data</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Chart</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="reportingProgressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Completion Breakdown</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export
                                    Data</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print Chart</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="completionBreakdownChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Indicators & Search (View Report Summary button removed) -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Active Indicators</h5>
                        <div class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="indicatorSearch"
                                    placeholder="Search indicators...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4" id="indicatorCardsContainer">
                        @foreach ($indicators as $indicator)
                            <div class="col-md-6 col-lg-4 indicator-card-wrapper">
                                <div class="card indicator-card" data-bs-toggle="modal"
                                    data-bs-target="#indicatorModal-{{ $indicator->IID }}">
                                    <div class="card-body">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $indicator->Indicator }}</h6>
                                            <p class="card-text text-muted small mb-0">
                                                {{ $indicator->SecondaryCategory }}</p>
                                        </div>
                                        <span
                                            class="indicator-status {{ isset($existingReports[$indicator->IID]) ? 'status-completed' : 'status-pending' }}">
                                            <i
                                                class="fas {{ isset($existingReports[$indicator->IID]) ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                            {{ isset($existingReports[$indicator->IID]) ? 'Reported' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($indicators as $indicator)
    @php
        $responseValue = old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '');
        $commentValue = old("comments.{$indicator->IID}", $existingComments[$indicator->IID] ?? '');
    @endphp
    <div class="modal modal-xl fade" id="indicatorModal-{{ $indicator->IID }}" tabindex="-1"
        aria-labelledby="indicatorModalLabel-{{ $indicator->IID }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="indicatorModalLabel-{{ $indicator->IID }}">
                        <i class="fas fa-clipboard-list me-2"></i>{{ $indicator->Indicator }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="indicatorTab-{{ $indicator->IID }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab-{{ $indicator->IID }}"
                                data-bs-toggle="tab" data-bs-target="#details-{{ $indicator->IID }}" type="button"
                                role="tab" aria-controls="details-{{ $indicator->IID }}" aria-selected="true">
                                <i class="fas fa-info-circle me-2"></i>Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reporting-tab-{{ $indicator->IID }}" data-bs-toggle="tab"
                                data-bs-target="#reporting-{{ $indicator->IID }}" type="button" role="tab"
                                aria-controls="reporting-{{ $indicator->IID }}" aria-selected="false">
                                <i class="fas fa-edit me-2"></i>Reporting
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab-{{ $indicator->IID }}" data-bs-toggle="tab"
                                data-bs-target="#history-{{ $indicator->IID }}" type="button" role="tab"
                                aria-controls="history-{{ $indicator->IID }}" aria-selected="false">
                                <i class="fas fa-history me-2"></i>History
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="indicatorTabContent-{{ $indicator->IID }}">
                        <div class="tab-pane fade show active" id="details-{{ $indicator->IID }}" role="tabpanel"
                            aria-labelledby="details-tab-{{ $indicator->IID }}">
                            <div class="row g-4">
                                @php
                                    $detailItems = [
                                        'Secondary Category' => $indicator->SecondaryCategory,
                                        'Definition' => $indicator->IndicatorDefinition,
                                        'Question' => $indicator->IndicatorQuestion,
                                        'Remarks' => $indicator->RemarksComments,
                                        'Source of Data' => $indicator->SourceOfData,
                                        'Response Type' => $indicator->ResponseType,
                                        'Reporting Period' => $indicator->ReportingPeriod,
                                        'Expected Target' => $indicator->ExpectedTarget,
                                        'Baseline PAD 2023' => $indicator->BaselinePAD2023,
                                        'Baseline 2024' => $indicator->Baseline2024,
                                        'Target Year One 2024' => $indicator->TargetYearOne2024,
                                        'Target Year Two 2025' => $indicator->TargetYearTwo2025,
                                        'Target Year Three 2026' => $indicator->TargetYearThree2026,
                                        'Target Year Four 2027' => $indicator->TargetYearFour2027,
                                        'Target Year Five 2028' => $indicator->TargetYearFive2028,
                                        'Target Year Six 2029' => $indicator->TargetYearSix2029,
                                        'Target Year Seven 2030' => $indicator->TargetYearSeven2030,
                                    ];
                                @endphp
                                @foreach ($detailItems as $label => $value)
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 text-muted">{{ $label }}</h6>
                                                @if (strlen($value) > 100)
                                                    <p class="card-text mb-0 collapsed"
                                                        id="{{ Str::slug($label) }}-{{ $indicator->IID }}"
                                                        style="height: 3em; overflow: hidden;">
                                                        {{ $value }}
                                                    </p>
                                                    <a href="#" class="btn btn-link btn-sm p-0 mt-2"
                                                        onclick="toggleCollapse('{{ Str::slug($label) }}-{{ $indicator->IID }}', event)">Show
                                                        More</a>
                                                @else
                                                    <p class="card-text mb-0">{{ $value }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reporting-{{ $indicator->IID }}" role="tabpanel"
                            aria-labelledby="reporting-tab-{{ $indicator->IID }}">
                            <form action="{{ route('indicator.submit') }}" method="POST"
                                id="form-{{ $indicator->IID }}">
                                @csrf
                                <input type="hidden" name="entity_id" value="{{ $entity->EntityID }}">
                                <input type="hidden" name="reporting_period" value="{{ $timeline->ReportingID }}">
                                <div class="mb-3">

                                    @if ($indicator->EntityID === 'RRF')
                                        <div class="alert alert-danger shadow-lg">
                                            ({{ $indicator->IndicatorQuestion }})
                                        </div>
                                    @endif

                                    <label class="form-label">Your Response
                                    </label>
                                    @switch($indicator->ResponseType)
                                        @case('Text')
                                            <textarea class="form-control" name="responses[{{ $indicator->IID }}]" rows="3"
                                                placeholder="Enter text response" {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>{{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') }}</textarea>
                                        @break

                                        @case('Number')
                                            <input type="number" class="form-control"
                                                name="responses[{{ $indicator->IID }}]" placeholder="Enter a number"
                                                value="{{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') }}"
                                                {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>
                                        @break

                                        @case('Boolean')
                                            <select class="form-select" name="responses[{{ $indicator->IID }}]"
                                                {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>
                                                <option value="">Select an option</option>
                                                <option value="1"
                                                    {{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') === '1' ? 'selected' : '' }}>
                                                    True</option>
                                                <option value="0"
                                                    {{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') === '0' ? 'selected' : '' }}>
                                                    False</option>
                                            </select>
                                        @break

                                        @case('Percentage')
                                            <div class="input-group">
                                                <input type="number" class="form-control"
                                                    name="responses[{{ $indicator->IID }}]" placeholder="Enter percentage"
                                                    value="{{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') }}"
                                                    min="0" max="100" step="0.01"
                                                    {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        @break

                                        @case('Yes/No')
                                            <select class="form-select" name="responses[{{ $indicator->IID }}]"
                                                {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>
                                                <option value="">Select an option</option>
                                                <option value="Yes"
                                                    {{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') === 'Yes' ? 'selected' : '' }}>
                                                    Yes</option>
                                                <option value="No"
                                                    {{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') === 'No' ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        @break

                                        @default
                                            <input type="text" class="form-control"
                                                name="responses[{{ $indicator->IID }}]" placeholder="Enter your response"
                                                value="{{ old("responses.{$indicator->IID}", $existingReports[$indicator->IID] ?? '') }}"
                                                {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>
                                    @endswitch
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Additional Comments</label>
                                    <textarea required class="form-control" name="comments[{{ $indicator->IID }}]" rows="3"
                                        placeholder="Optional: Provide context" {{ $timeline->status === 'Completed' ? 'disabled' : '' }}>{{ old("comments.{$indicator->IID}", $existingComments[$indicator->IID] ?? '') }}</textarea>
                                </div>
                                @if ($timeline->status !== 'Completed')
                                    <button type="submit" class="btn btn-primary"
                                        onclick="disableSubmitButton('form-{{ $indicator->IID }}', this)">
                                        <i class="fas fa-save me-2"></i>Save Response
                                    </button>
                                @endif
                            </form>
                        </div>
                        <div class="tab-pane fade" id="history-{{ $indicator->IID }}" role="tabpanel"
                            aria-labelledby="history-tab-{{ $indicator->IID }}">
                            <!-- Target Metrics Summary -->
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered details-table">
                                    <thead>
                                        <tr>
                                            <th>Baseline PAD 2023</th>
                                            <th>Baseline 2024</th>
                                            <th>Target 1 (2024)</th>
                                            <th>Target 2 (2025)</th>
                                            <th>Target 3 (2026)</th>
                                            <th>Target 4 (2027)</th>
                                            <th>Target 5 (2028)</th>
                                            <th>Target 6 (2029)</th>
                                            <th>Target 7 (2030)</th>
                                            <th>Expected Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $indicator->BaselinePAD2023 }}</td>
                                            <td>{{ $indicator->Baseline2024 }}</td>
                                            <td>{{ $indicator->TargetYearOne2024 }}</td>
                                            <td>{{ $indicator->TargetYearTwo2025 }}</td>
                                            <td>{{ $indicator->TargetYearThree2026 }}</td>
                                            <td>{{ $indicator->TargetYearFour2027 }}</td>
                                            <td>{{ $indicator->TargetYearFive2028 }}</td>
                                            <td>{{ $indicator->TargetYearSix2029 }}</td>
                                            <td>{{ $indicator->TargetYearSeven2030 }}</td>
                                            <td>{{ $indicator->ExpectedTarget }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Historical Reports Table -->
                            @if (isset($indicator->history) && count($indicator->history))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Report Name</th>
                                                <th>Year</th>
                                                <th>Response</th>
                                                <th>Comments</th>
                                                <th>Reported By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($indicator->history as $hist)
                                                <tr>
                                                    <td>{{ $hist->ReportName }}</td>
                                                    <td>{{ $hist->Year }}</td>
                                                    <td>{{ $hist->Response }}</td>
                                                    <td>{{ $hist->Comments }}</td>
                                                    <td>{{ $hist->ReportedBy }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No historical data available for this indicator.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="guidance-text">
                        Once saved, your response is recorded and is exclusively tied to the selected report
                        (<strong>{{ $timeline->ReportName }}</strong>) and year
                        (<strong>{{ $timeline->Year }}</strong>). You can update it until the reporting period is
                        marked as Completed.
                    </small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-xmark me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('reportingProgressChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Total', 'Reported', 'Remaining'],
                datasets: [{
                    label: 'Indicators',
                    data: [{{ $totalIndicators }}, {{ $reportedIndicators }},
                        {{ $totalIndicators - $reportedIndicators }}
                    ],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx2 = document.getElementById('completionBreakdownChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Reported', 'Remaining'],
                datasets: [{
                    data: [{{ $reportedIndicators }},
                        {{ $totalIndicators - $reportedIndicators }}
                    ],
                    backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 205, 86)']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });

    function disableSubmitButton(formId, btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        document.getElementById(formId).submit();
    }

    document.getElementById('indicatorSearch').addEventListener('keyup', function() {
        var query = this.value.toLowerCase();
        document.querySelectorAll('.indicator-card-wrapper').forEach(function(card) {
            var indicatorName = card.querySelector('.card-title').textContent.toLowerCase();
            card.style.display = indicatorName.includes(query) ? "" : "none";
        });
    });

    function toggleCollapse(id, event) {
        event.preventDefault();
        var element = document.getElementById(id);
        var link = event.target;
        if (element.classList.contains('collapsed')) {
            element.style.height = 'auto';
            element.classList.remove('collapsed');
            link.textContent = 'Show Less';
        } else {
            element.style.height = '3em';
            element.classList.add('collapsed');
            link.textContent = 'Show More';
        }
    }
</script>
