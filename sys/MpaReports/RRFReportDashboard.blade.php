@php
    // Set defaults if not provided.
    $selectedCluster = $selectedCluster ?? 'All clusters';
    $exportReport = isset($analyticsData[0]['timeline']) ? $analyticsData[0]['timeline']->ReportName : $reportType;
    // Build mapping: EntityID => Entity (name)
    $entities = DB::table('mpa_entities')->pluck('Entity', 'EntityID')->toArray();
@endphp

<div class="container-fluid">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    RRF Dashboard: {{ $reportType }} {{ $selectedYear }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{ route('rrf.report.exportExcel') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="report_type" value="{{ $reportType }}">
                    <input type="hidden" name="reporting_year" value="{{ $selectedYear }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Overall Reporting Completeness</h3>
                </div>
                <div class="card-body">
                    <div id="completenessChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timeline Reports</h3>
                </div>
                <div class="card-body">
                    <div class="timeline-wizard">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($analyticsData as $index => $timelineData)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        id="timeline-tab-{{ $index }}" data-bs-toggle="tab"
                                        href="#timeline-content-{{ $index }}" role="tab"
                                        aria-controls="timeline-content-{{ $index }}"
                                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $timelineData['timeline']->ReportName }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content mt-3">
                            @foreach ($analyticsData as $index => $timelineData)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="timeline-content-{{ $index }}" role="tabpanel"
                                    aria-labelledby="timeline-tab-{{ $index }}">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Indicator</th>
                                                    <th>Response Type</th>
                                                    <th>Target</th>
                                                    <th>Achieved</th>
                                                    <th>Difference</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($timelineData['indicators'] as $indicatorData)
                                                    <tr>
                                                        <td>{{ $indicatorData['indicator']->Indicator }}</td>
                                                        <td>{{ $indicatorData['indicator']->ResponseType }}</td>
                                                        <td>{{ $indicatorData['targetValue'] }}</td>
                                                        <td>
                                                            @if ($indicatorData['indicator']->ResponseType === 'Yes/No')
                                                                {{ $indicatorData['computedValue']['yesPercentage'] }}%
                                                            @elseif($indicatorData['indicator']->ResponseType === 'Number')
                                                                {{ $indicatorData['computedValue']['sum'] }}
                                                            @endif
                                                        </td>
                                                        <td
                                                            class="{{ $indicatorData['difference'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $indicatorData['difference'] }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#detailModal{{ $indicatorData['indicator']->id }}">
                                                                <i class="fas fa-eye me-1"></i> View Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($analyticsData as $timelineData)
    @foreach ($timelineData['indicators'] as $indicatorData)
        <div class="modal modal-blur fade" id="detailModal{{ $indicatorData['indicator']->id }}" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $indicatorData['indicator']->Indicator }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="historicalChart{{ $indicatorData['indicator']->id }}" style="height: 300px;"></div>
                        <div class="table-responsive mt-4">
                            <table class="table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>Entity</th>
                                        <th>Response</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($indicatorData['historicalData'] as $historyItem)
                                        <tr>
                                            <td>{{ $entities[$historyItem->EntityID] ?? $historyItem->EntityID }}</td>
                                            <td>{{ $historyItem->Response }}</td>
                                            <td>{{ $historyItem->Comments }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Overall Completeness Chart
        var completenessData = @json($completenessData);
        var completenessOptions = {
            series: [{
                name: 'Expected',
                data: completenessData.map(item => item.expectedCount)
            }, {
                name: 'Reported',
                data: completenessData.map(item => item.reportedCount)
            }, {
                name: 'Completeness (%)',
                data: completenessData.map(item => item.completeness)
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
                },
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
                categories: completenessData.map(item => item.timeline.ReportName),
            },
            yaxis: {
                title: {
                    text: 'Count / Percentage'
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
            },
            colors: ['#206bc4', '#79a6dc', '#bfe399']
        };
        var completenessChart = new ApexCharts(document.querySelector("#completenessChart"),
            completenessOptions);
        completenessChart.render();

        // Indicator Detail Charts
        @foreach ($analyticsData as $timelineData)
            @foreach ($timelineData['indicators'] as $indicatorData)
                (function() {
                    var indicatorId = {{ $indicatorData['indicator']->id }};
                    var historical = @json($indicatorData['historicalData']);
                    var responseType = "{{ $indicatorData['indicator']->ResponseType }}".trim();
                    var totalExpected = {{ $totalCountries }};
                    var totalActual = historical.length;
                    var detailSeries = [];

                    if (responseType === 'Yes/No') {
                        var yesCount = 0,
                            noCount = 0;
                        historical.forEach(function(item) {
                            var resp = String(item.Response).toLowerCase().trim();
                            if (resp === 'yes') {
                                yesCount++;
                            } else if (resp === 'no') {
                                noCount++;
                            }
                        });
                        var score = {{ $indicatorData['computedValue']['yesPercentage'] ?? 0 }};
                        detailSeries = [{
                                name: 'Expected',
                                data: [totalExpected]
                            },
                            {
                                name: 'Actual',
                                data: [totalActual]
                            },
                            {
                                name: 'Yes',
                                data: [yesCount]
                            },
                            {
                                name: 'No',
                                data: [noCount]
                            },
                            {
                                name: 'Score (%)',
                                data: [score]
                            }
                        ];
                    } else if (responseType === 'Number') {
                        var sumScore = {{ $indicatorData['computedValue']['sum'] ?? 0 }};
                        detailSeries = [{
                                name: 'Expected',
                                data: [totalExpected]
                            },
                            {
                                name: 'Actual',
                                data: [totalActual]
                            },
                            {
                                name: 'Score',
                                data: [sumScore]
                            }
                        ];
                    } else {
                        detailSeries = [{
                                name: 'Expected',
                                data: [totalExpected]
                            },
                            {
                                name: 'Actual',
                                data: [totalActual]
                            }
                        ];
                    }

                    var detailOptions = {
                        series: detailSeries,
                        chart: {
                            type: 'bar',
                            height: 300
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '50%',
                                endingShape: 'rounded'
                            },
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
                            categories: [''],
                        },
                        yaxis: {
                            title: {
                                text: 'Count / Score'
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
                        },
                        colors: ['#206bc4', '#79a6dc', '#bfe399', '#ffc107', '#f66d9b']
                    };

                    var detailChart = new ApexCharts(document.querySelector("#historicalChart" +
                        indicatorId), detailOptions);
                    detailChart.render();
                })();
            @endforeach
        @endforeach
    });
</script>
