{{-- resources/views/MpaReports/ReportingCompleteness.blade.php --}}
<div class="container-fluid px-4 py-5">
    <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col-auto">
            <h1 class="page-title mb-0 text-primary">
                <i class="fas fa-chart-pie me-2"></i>Reporting Completeness Dashboard
            </h1>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center">
                <span class="text-muted me-2">Reporting Year:</span>
                <h3 class="mb-0">{{ $selectedYear }}</h3>
                <a href="{{ route('mpa.reports.completeness.select_year') }}"
                    class="btn btn-outline-primary btn-sm ms-3">
                    <i class="fas fa-calendar-alt me-1"></i>Change Year
                </a>
            </div>
        </div>
    </div>

    <div class="alert alert-info mb-4" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        All reporting metrics and functionality are strictly based on the selected reporting year.
    </div>

    @if ($isAdmin)
        <div class="alert alert-warning mb-4" role="alert">
            <i class="fas fa-user-shield me-2"></i>
            Admin view: Displaying data for all entities.
        </div>
    @else
        <div class="alert alert-info mb-4" role="alert">
            <i class="fas fa-user me-2"></i>
            Viewing data for entity: {{ $user->EntityID }}
        </div>
    @endif

    <div class="wizard-navigation mb-4">
        <ul class="nav nav-pills nav-justified">
            @foreach ($analyticsData as $index => $timelineData)
                <li class="nav-item">
                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}" href="#step-{{ $index }}"
                        data-bs-toggle="tab">
                        {{ $timelineData['timeline']->ReportName }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content">
        @foreach ($analyticsData as $timelineIndex => $timelineData)
            <div class="tab-pane fade {{ $timelineIndex === 0 ? 'show active' : '' }}" id="step-{{ $timelineIndex }}">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i>{{ $timelineData['timeline']->ReportName }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div id="completenessChart{{ $timelineIndex }}" style="height: 300px;"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div id="indicatorChart{{ $timelineIndex }}" style="height: 300px;"></div>
                            </div>
                        </div>

                        @foreach ($timelineData['entities'] as $entityIndex => $entityData)
                            @php
                                $expectedMap = [];
                                foreach ($entityData['expectedIndicators'] as $expIndicator) {
                                    $expectedMap[$expIndicator->IID] = $expIndicator;
                                }
                            @endphp
                            <div class="entity-section mt-4 pt-4 border-top">
                                <h3 class="text-primary mb-3">
                                    <i class="fas fa-building me-2"></i>{{ $entityData['entity']->Entity }}
                                </h3>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Expected Indicators</h5>
                                                <p class="card-text display-6">{{ $entityData['expectedCount'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Reported Indicators</h5>
                                                <p class="card-text display-6">{{ $entityData['reportedCount'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Completeness</h5>
                                                <p class="card-text display-6">
                                                    {{ number_format($entityData['completeness'], 2) }}%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $entityData['completeness'] }}%;"
                                        aria-valuenow="{{ $entityData['completeness'] }}" aria-valuemin="0"
                                        aria-valuemax="100">{{ number_format($entityData['completeness'], 2) }}%</div>
                                </div>

                                @if ($entityData['missingIndicators']->count() > 0)
                                    <div class="mt-4">
                                        <h4 class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Missing Indicators
                                        </h4>
                                        <ul class="list-group">
                                            @foreach ($entityData['missingIndicators'] as $missingIndicator)
                                                <li class="list-group-item">
                                                    @if (property_exists($missingIndicator, 'Indicator'))
                                                        {{ $missingIndicator->Indicator }}
                                                    @else
                                                        {{ $missingIndicator->IID }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#entityDetails{{ $timelineIndex }}_{{ $entityIndex }}"
                                        aria-expanded="false">
                                        <i class="fas fa-chevron-down me-1"></i>View Details
                                    </button>
                                    <div class="collapse mt-3"
                                        id="entityDetails{{ $timelineIndex }}_{{ $entityIndex }}">
                                        <div class="card card-body">
                                            <h5>Expected Indicators</h5>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Indicator</th>
                                                            <th>Category</th>
                                                            <th>Response Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entityData['expectedIndicators'] as $indicator)
                                                            <tr>
                                                                <td>{{ $indicator->Indicator }}</td>
                                                                <td>{{ $indicator->SecondaryCategory }}</td>
                                                                <td>{{ $indicator->ResponseType }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <h5 class="mt-4">Reported Indicators</h5>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Indicator</th>
                                                            <th>Response</th>
                                                            <th>Reported By</th>
                                                            <th>Comments</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($entityData['reportedReports'] as $report)
                                                            <tr>
                                                                <td>
                                                                    @if (isset($expectedMap[$report->IID]) && property_exists($expectedMap[$report->IID], 'Indicator'))
                                                                        {{ $expectedMap[$report->IID]->Indicator }}
                                                                    @else
                                                                        {{ $report->IID }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $report->Response }}</td>
                                                                <td>{{ $report->ReportedBy }}</td>
                                                                <td>{{ $report->Comments }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <h5 class="mt-4">Historical Data</h5>
                                            @foreach ($entityData['historicalData'] as $iid => $history)
                                                @php
                                                    $expectedIndicator = $entityData['expectedIndicators']->firstWhere(
                                                        'IID',
                                                        $iid,
                                                    );
                                                    $indicatorName = $expectedIndicator
                                                        ? $expectedIndicator->Indicator
                                                        : $iid;
                                                @endphp
                                                @if ($history->isNotEmpty())
                                                    <div class="mb-3">
                                                        <h6 class="mb-1">
                                                            {{ $indicatorName }}
                                                            <button class="btn btn-sm btn-outline-secondary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#historyModal{{ $timelineIndex }}_{{ $entityIndex }}_{{ $iid }}">
                                                                View Historical Data
                                                            </button>
                                                        </h6>
                                                        <div class="modal fade"
                                                            id="historyModal{{ $timelineIndex }}_{{ $entityIndex }}_{{ $iid }}"
                                                            tabindex="-1"
                                                            aria-labelledby="historyModalLabel{{ $timelineIndex }}_{{ $entityIndex }}_{{ $iid }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-fullscreen">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="historyModalLabel{{ $timelineIndex }}_{{ $entityIndex }}_{{ $iid }}">
                                                                            Historical Data for {{ $indicatorName }}
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="table-responsive">
                                                                            <table
                                                                                class="table table-sm table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Year</th>
                                                                                        <th>Report Name</th>
                                                                                        <th>Response</th>
                                                                                        <th>Comments</th>
                                                                                        <th>Reported By</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($history as $record)
                                                                                        <tr>
                                                                                            <td>{{ $record->Year }}
                                                                                            </td>
                                                                                            <td>{{ $record->ReportName }}
                                                                                            </td>
                                                                                            <td>{{ $record->Response }}
                                                                                            </td>
                                                                                            <td>{{ $record->Comments }}
                                                                                            </td>
                                                                                            <td>{{ $record->ReportedBy }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($analyticsData as $timelineIndex => $timelineData)
            var entities = [];
            @foreach ($timelineData['entities'] as $entityData)
                entities.push("{{ $entityData['entity']->Entity }}");
            @endforeach

            var completenessData = @json(array_column($timelineData['entities'], 'completeness'));
            var expectedCounts = @json(array_column($timelineData['entities'], 'expectedCount'));
            var reportedCounts = @json(array_column($timelineData['entities'], 'reportedCount'));

            var completenessOptions = {
                series: [{
                    name: 'Completeness',
                    data: completenessData
                }],
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: entities
                },
                yaxis: {
                    title: {
                        text: 'Completeness (%)'
                    },
                    max: 100
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%"
                        }
                    }
                }
            };

            var completenessChart = new ApexCharts(document.querySelector(
                "#completenessChart{{ $timelineIndex }}"), completenessOptions);
            completenessChart.render();

            var indicatorOptions = {
                series: [{
                        name: 'Expected Indicators',
                        data: expectedCounts
                    },
                    {
                        name: 'Reported Indicators',
                        data: reportedCounts
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: entities
                },
                yaxis: {
                    title: {
                        text: 'Number of Indicators'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val
                        }
                    }
                }
            };

            var indicatorChart = new ApexCharts(document.querySelector("#indicatorChart{{ $timelineIndex }}"),
                indicatorOptions);
            indicatorChart.render();
        @endforeach
    });
</script>
