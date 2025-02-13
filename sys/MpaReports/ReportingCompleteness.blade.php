{{-- resources/views/MpaReports/ReportingCompleteness.blade.php --}}

@php
    /*
   This view removes all baseline/extra columns and focuses on:
     - The selected report year’s target (if any),
     - The user’s historical submissions (report name, response, comment),
     - A wizard approach for quick navigation: 
         1) Select an indicator,
         2) Show the target for the selected year & let user pick a past report,
         3) Display the selected report’s performance details.
*/
    $timelineSums = [];
    $entitySums = [];

    foreach ($analyticsData as $tData) {
        $timeline = $tData['timeline'];
        $timelineId = $timeline->id;

        if (!isset($timelineSums[$timelineId])) {
            $timelineSums[$timelineId] = [
                'name' => $timeline->ReportName ?? 'Untitled Report',
                'sum' => 0,
                'count' => 0,
            ];
        }

        foreach ($tData['entities'] as $entData) {
            $entityObj = $entData['entity'];
            $completeness = (float) $entData['completeness'];

            $timelineSums[$timelineId]['sum'] += $completeness;
            $timelineSums[$timelineId]['count']++;

            $entityId = $entityObj->EntityID;
            if (!isset($entitySums[$entityId])) {
                $entitySums[$entityId] = [
                    'name' => $entityObj->Entity,
                    'sum' => 0,
                    'count' => 0,
                ];
            }
            $entitySums[$entityId]['sum'] += $completeness;
            $entitySums[$entityId]['count']++;
        }
    }

    $timelineChartLabels = [];
    $timelineChartData = [];
    foreach ($timelineSums as $tid => $info) {
        if ($info['count'] > 0) {
            $avg = round($info['sum'] / $info['count'], 2);
            $timelineChartLabels[] = $info['name'];
            $timelineChartData[] = $avg;
        }
    }

    $entityChartLabels = [];
    $entityChartData = [];
    foreach ($entitySums as $eid => $info) {
        if ($info['count'] > 0) {
            $avg = round($info['sum'] / $info['count'], 2);
            $entityChartLabels[] = $info['name'];
            $entityChartData[] = $avg;
        }
    }

    // Map year->column for target
    $yearToColumnMap = [
        '2023' => 'BaselinePAD2023',
        '2024' => 'TargetYearOne2024',
        '2025' => 'TargetYearTwo2025',
        '2026' => 'TargetYearThree2026',
        '2027' => 'TargetYearFour2027',
        '2028' => 'TargetYearFive2028',
        '2029' => 'TargetYearSix2029',
        '2030' => 'TargetYearSeven2030',
    ];
    $highlightColumn = $yearToColumnMap[$selectedYear] ?? null;
@endphp

<div class="container-fluid py-4 bg-light">
    <div class="text-center mb-5">
        <h2 class="text-primary fw-bold">Reporting Completeness for Year: {{ $selectedYear }}</h2>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            Below is an overview of reporting completeness across timelines and entities.
            Click <strong>"View Data"</strong> on any card for detailed insights.
        </p>
    </div>

    {{-- Charts --}}
    <div class="row mb-5 g-4 justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">Avg Completeness by Timeline</h5>
                    <div id="chartTimeline"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary">Avg Completeness by Entity</h5>
                    <div id="chartEntities"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Cards --}}
    <div class="row g-4">
        @foreach ($analyticsData as $idx => $data)
            @php
                $timeline = $data['timeline'];
                $entities = $data['entities'];
            @endphp
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title text-primary mb-2">
                            {{ $timeline->ReportName ?? 'Untitled Report' }}
                        </h4>
                        <h6 class="text-muted mb-3">
                            Reporting Type: {{ $timeline->Type }} <br>
                            Year: {{ $timeline->Year }}
                        </h6>
                        @if (!empty($timeline->Description))
                            <p class="small text-muted">{{ $timeline->Description }}</p>
                        @endif
                        <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#modalTimeline{{ $timeline->id }}">
                            <i class="fas fa-chart-line me-1"></i> View Data
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal for Detailed Data --}}
            <div class="modal fade" id="modalTimeline{{ $timeline->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold">
                                {{ $timeline->ReportName ?? 'Untitled Report' }}
                                | {{ $timeline->Type }} - {{ $timeline->Year }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            @if (count($entities) === 0)
                                <p class="text-center text-danger"><strong>No entities found.</strong></p>
                            @else
                                @php
                                    $sumIndicators = 0;
                                    foreach ($entities as $ent) {
                                        $sumIndicators += $ent['expectedCount'];
                                    }
                                @endphp

                                @if ($sumIndicators === 0)
                                    <div class="alert alert-warning text-center">
                                        <h5>No Indicators Found</h5>
                                    </div>
                                @else
                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                        @foreach ($entities as $eIndex => $entData)
                                            @php
                                                $entObj = $entData['entity'];
                                            @endphp
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link @if ($eIndex === 0) active @endif"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#entity-{{ $timeline->id }}-{{ $entObj->id }}"
                                                    type="button"
                                                    aria-selected="{{ $eIndex === 0 ? 'true' : 'false' }}">
                                                    {{ $entObj->Entity }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content">
                                        @foreach ($entities as $eIndex => $entData)
                                            @php
                                                $entObj = $entData['entity'];
                                                $completeness = $entData['completeness'];
                                                $expectedCount = $entData['expectedCount'];
                                                $reportedCount = $entData['reportedCount'];
                                                $missingIndicators = $entData['missingIndicators'];
                                                $expectedIndicators = $entData['expectedIndicators'];
                                                $historicalData = $entData['historicalData'];
                                            @endphp

                                            <div class="tab-pane fade @if ($eIndex === 0) show active @endif"
                                                id="entity-{{ $timeline->id }}-{{ $entObj->id }}">
                                                @if ($expectedCount === 0)
                                                    <p class="text-danger">
                                                        No indicators found for <strong>{{ $entObj->Entity }}</strong>.
                                                    </p>
                                                @else
                                                    <div class="row mb-3">
                                                        <div class="col-md-4">
                                                            <div class="border bg-white p-3 rounded shadow-sm">
                                                                <span class="text-muted">Expected:</span>
                                                                <h4 class="mb-0 text-success">{{ $expectedCount }}</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border bg-white p-3 rounded shadow-sm">
                                                                <span class="text-muted">Reported:</span>
                                                                <h4 class="mb-0 text-info">{{ $reportedCount }}</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="border bg-white p-3 rounded shadow-sm">
                                                                <span class="text-muted">Completeness(%):</span>
                                                                <h4 class="mb-0 text-primary">{{ $completeness }}</h4>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <ul class="nav nav-pills mb-3">
                                                        <li class="nav-item">
                                                            <button class="nav-link active" data-bs-toggle="tab"
                                                                data-bs-target="#ov-{{ $timeline->id }}-{{ $entObj->id }}">
                                                                Overview
                                                            </button>
                                                        </li>
                                                        <li class="nav-item">
                                                            <button class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#miss-{{ $timeline->id }}-{{ $entObj->id }}">
                                                                Missing
                                                            </button>
                                                        </li>
                                                        <li class="nav-item">
                                                            <button class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#hist-{{ $timeline->id }}-{{ $entObj->id }}">
                                                                Historical Data Wizard
                                                            </button>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content">
                                                        {{-- Overview --}}
                                                        <div class="tab-pane fade show active p-3"
                                                            id="ov-{{ $timeline->id }}-{{ $entObj->id }}">
                                                            <h5 class="fw-bold text-primary mb-3">
                                                                {{ $entObj->Entity }} - Overview
                                                            </h5>
                                                            <h6 class="text-secondary">
                                                                All Expected Indicators ({{ $expectedCount }}):
                                                            </h6>
                                                            <ul class="small" style="max-height:150px; overflow:auto;">
                                                                @foreach ($expectedIndicators as $ind)
                                                                    <li style="font-size:14px;">
                                                                        {{ $ind->Indicator }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>

                                                        {{-- Missing --}}
                                                        <div class="tab-pane fade p-3"
                                                            id="miss-{{ $timeline->id }}-{{ $entObj->id }}">
                                                            <h5 class="fw-bold text-warning mb-3">
                                                                Missing ({{ count($missingIndicators) }})
                                                            </h5>
                                                            @if (count($missingIndicators) === 0)
                                                                <p class="text-success">All required indicators are
                                                                    reported.</p>
                                                            @else
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-striped table-bordered align-middle">
                                                                        <thead class="table-dark">
                                                                            <tr>
                                                                                <th>Indicator</th>
                                                                                <th>Primary Cat</th>
                                                                                <th>Secondary Cat</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($missingIndicators as $mInd)
                                                                                <tr>
                                                                                    <td style="font-size:14px;">
                                                                                        {{ $mInd->Indicator }}</td>
                                                                                    <td style="font-size:14px;">
                                                                                        {{ $mInd->PrimaryCategory }}
                                                                                    </td>
                                                                                    <td style="font-size:14px;">
                                                                                        {{ $mInd->SecondaryCategory }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Historical Data Wizard (Performance-Focused) --}}
                                                        <div class="tab-pane fade p-3"
                                                            id="hist-{{ $timeline->id }}-{{ $entObj->id }}">
                                                            <h5 class="fw-bold text-secondary mb-3">
                                                                Intelligent Wizard: Indicator Performance
                                                            </h5>
                                                            <p class="small text-muted">
                                                                Quickly navigate an indicator’s target for the selected
                                                                year
                                                                and see any past reports (name, response, comment).
                                                            </p>

                                                            <div class="wizard-container"
                                                                id="wizard-{{ $timeline->id }}-{{ $entObj->id }}">
                                                                {{-- Step Indicators --}}
                                                                <div
                                                                    class="wizard-steps d-flex align-items-center justify-content-center mb-4">
                                                                    <div class="wizard-step-item active"
                                                                        data-step="1">
                                                                        <span class="wizard-step-number">1</span>
                                                                        <span class="wizard-step-label">Select
                                                                            Indicator</span>
                                                                    </div>
                                                                    <div class="wizard-step-item" data-step="2">
                                                                        <span class="wizard-step-number">2</span>
                                                                        <span class="wizard-step-label">Select Past
                                                                            Report</span>
                                                                    </div>
                                                                    <div class="wizard-step-item" data-step="3">
                                                                        <span class="wizard-step-number">3</span>
                                                                        <span class="wizard-step-label">Performance
                                                                            Details</span>
                                                                    </div>
                                                                </div>

                                                                {{-- Step 1: Choose Indicator --}}
                                                                <div class="wizard-step-content show" data-step="1">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">
                                                                            Choose an Indicator:
                                                                        </label>
                                                                        <select
                                                                            class="form-select wizardIndicatorSelect"
                                                                            data-indicators='@json($expectedIndicators)'
                                                                            data-hist='@json($historicalData)'
                                                                            data-highlightcol='{{ $highlightColumn }}'>
                                                                            <option value="" selected disabled>
                                                                                Select...</option>
                                                                            @foreach ($expectedIndicators as $oneInd)
                                                                                <option value="{{ $oneInd->IID }}">
                                                                                    {{ $oneInd->Indicator }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <button class="btn btn-primary wizard-next-btn"
                                                                        disabled>
                                                                        Next <i class="fas fa-arrow-right ms-1"></i>
                                                                    </button>
                                                                </div>

                                                                {{-- Step 2: Choose Past Report (Performance) --}}
                                                                <div class="wizard-step-content" data-step="2">
                                                                    <div class="mb-3 border rounded p-3 bg-white">
                                                                        <div class="mb-2">
                                                                            <strong>Selected Year’s Target:</strong>
                                                                            <span
                                                                                id="wizSelectedYearTarget-{{ $timeline->id }}-{{ $entObj->id }}"
                                                                                class="ms-1 fw-bold text-primary"></span>
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <strong>Indicator:</strong>
                                                                            <span
                                                                                id="wizIndicatorName-{{ $timeline->id }}-{{ $entObj->id }}"
                                                                                class="ms-1"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">
                                                                            Select a Past Report to View Performance:
                                                                        </label>
                                                                        <select class="form-select wizardReportSelect"
                                                                            disabled>
                                                                            <option value="" selected disabled>
                                                                                Select a Report</option>
                                                                            <!-- Dynamically filled from Step 1 selection -->
                                                                        </select>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <button
                                                                            class="btn btn-secondary wizard-prev-btn">
                                                                            <i class="fas fa-arrow-left me-1"></i> Back
                                                                        </button>
                                                                        <button class="btn btn-primary wizard-next-btn"
                                                                            disabled>
                                                                            Next <i
                                                                                class="fas fa-arrow-right ms-1"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                {{-- Step 3: Performance Details --}}
                                                                <div class="wizard-step-content" data-step="3">
                                                                    <div class="bg-white border rounded p-3 mb-3">
                                                                        <div class="fw-bold mb-2"
                                                                            style="font-size:15px;">
                                                                            <span
                                                                                id="wizReportName-{{ $timeline->id }}-{{ $entObj->id }}"></span>
                                                                        </div>
                                                                        <div class="mb-1">
                                                                            <strong>Response:</strong>
                                                                            <span
                                                                                id="wizResponse-{{ $timeline->id }}-{{ $entObj->id }}"></span>
                                                                        </div>
                                                                        <div class="mb-1">
                                                                            <strong>Comment:</strong>
                                                                            <span
                                                                                id="wizComment-{{ $timeline->id }}-{{ $entObj->id }}"></span>
                                                                        </div>
                                                                        <div class="small text-muted">
                                                                            Reported By:
                                                                            <span
                                                                                id="wizReportedBy-{{ $timeline->id }}-{{ $entObj->id }}"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <button
                                                                            class="btn btn-secondary wizard-prev-btn">
                                                                            <i class="fas fa-arrow-left me-1"></i> Back
                                                                        </button>
                                                                        <button
                                                                            class="btn btn-success wizard-finish-btn">
                                                                            Finish
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>{{-- end wizard-container --}}
                                                        </div>
                                                    </div>{{-- end tab-content --}}
                                                @endif
                                            </div>{{-- end tab-pane --}}
                                        @endforeach
                                    </div>{{-- end tab-content --}}
                                @endif
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>{{-- end modal-content --}}
                </div>{{-- end modal-dialog --}}
            </div>{{-- end modal --}}
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart: Timelines
        var timelineOptions = {
            series: [{
                name: "Completeness",
                data: @json($timelineChartData)
            }],
            chart: {
                type: 'bar',
                height: 300
            },
            xaxis: {
                categories: @json($timelineChartLabels)
            },
            yaxis: {
                max: 100,
                labels: {
                    formatter: (val) => val + "%"
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => val + "%"
            },
            title: {
                text: 'Avg Completeness by Timeline',
                align: 'center'
            },
            colors: ['#008FFB'],
            plotOptions: {
                bar: {
                    borderRadius: 4
                }
            }
        };
        new ApexCharts(document.querySelector("#chartTimeline"), timelineOptions).render();

        // Chart: Entities
        var entityOptions = {
            series: [{
                name: "Completeness",
                data: @json($entityChartData)
            }],
            chart: {
                type: 'bar',
                height: 300
            },
            xaxis: {
                categories: @json($entityChartLabels)
            },
            yaxis: {
                max: 100,
                labels: {
                    formatter: (val) => val + "%"
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => val + "%"
            },
            title: {
                text: 'Avg Completeness by Entity',
                align: 'center'
            },
            colors: ['#00E396'],
            plotOptions: {
                bar: {
                    borderRadius: 4
                }
            }
        };
        new ApexCharts(document.querySelector("#chartEntities"), entityOptions).render();
    });
</script>

{{-- Wizard JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wizardContainers = document.querySelectorAll('.wizard-container');

        wizardContainers.forEach((wizardEl) => {
            let currentStep = 1;
            const stepContents = wizardEl.querySelectorAll('.wizard-step-content');
            const stepItems = wizardEl.querySelectorAll('.wizard-step-item');

            const nextBtns = wizardEl.querySelectorAll('.wizard-next-btn');
            const prevBtns = wizardEl.querySelectorAll('.wizard-prev-btn');
            const finishBtn = wizardEl.querySelector('.wizard-finish-btn');

            // Step 1 Elements
            const indicatorSelect = wizardEl.querySelector('.wizardIndicatorSelect');

            // Step 2 Elements
            const reportSelect = wizardEl.querySelector('.wizardReportSelect');
            const yearTargetEl = wizardEl.querySelector(
                `#wizSelectedYearTarget-${wizardEl.id.split('-').slice(1).join('-')}`);
            const indicatorNameEl = wizardEl.querySelector(
                `#wizIndicatorName-${wizardEl.id.split('-').slice(1).join('-')}`);

            // Step 3 Elements
            const reportNameEl = wizardEl.querySelector(
                `#wizReportName-${wizardEl.id.split('-').slice(1).join('-')}`);
            const responseEl = wizardEl.querySelector(
                `#wizResponse-${wizardEl.id.split('-').slice(1).join('-')}`);
            const commentEl = wizardEl.querySelector(
                `#wizComment-${wizardEl.id.split('-').slice(1).join('-')}`);
            const reportedByEl = wizardEl.querySelector(
                `#wizReportedBy-${wizardEl.id.split('-').slice(1).join('-')}`);

            // Show step
            function goToStep(step) {
                stepContents.forEach((item) => item.classList.remove('show'));
                stepItems.forEach((sItem) => sItem.classList.remove('active'));

                const targetContent = wizardEl.querySelector(
                    `.wizard-step-content[data-step="${step}"]`);
                const targetItem = wizardEl.querySelector(`.wizard-step-item[data-step="${step}"]`);
                if (targetContent && targetItem) {
                    targetContent.classList.add('show');
                    targetItem.classList.add('active');
                }
                currentStep = step;
            }

            // Step 1: On Indicator select
            if (indicatorSelect) {
                indicatorSelect.addEventListener('change', function() {
                    const step1Next = wizardEl.querySelector(
                        '.wizard-step-content[data-step="1"] .wizard-next-btn');
                    if (step1Next) step1Next.disabled = false;
                });
            }

            // Next Buttons
            nextBtns.forEach((btn) => {
                btn.addEventListener('click', function() {
                    if (currentStep === 1) {
                        // We have chosen an indicator
                        const indicatorsData = JSON.parse(indicatorSelect.dataset
                            .indicators || '[]');
                        const histData = JSON.parse(indicatorSelect.dataset.hist ||
                            '{}');
                        const highlightCol = indicatorSelect.dataset.highlightcol;
                        const chosenIID = indicatorSelect.value;

                        // Find the chosen indicator
                        let foundIndicator = null;
                        for (let i = 0; i < indicatorsData.length; i++) {
                            if (indicatorsData[i].IID === chosenIID) {
                                foundIndicator = indicatorsData[i];
                                break;
                            }
                        }

                        if (foundIndicator) {
                            // Step 2: Show the target for the selected year
                            // If highlightCol is present, that column will have the 'target' 
                            let yearTargetVal = highlightCol && foundIndicator[
                                    highlightCol] ?
                                foundIndicator[highlightCol] :
                                'N/A';
                            if (yearTargetEl) {
                                yearTargetEl.textContent = yearTargetVal;
                            }
                            if (indicatorNameEl) {
                                indicatorNameEl.textContent = foundIndicator.Indicator;
                            }

                            // Step 2: Populate the report dropdown from histData
                            if (reportSelect) {
                                reportSelect.innerHTML =
                                    `<option value="" disabled selected>Select a Report</option>`;
                                // histData[chosenIID] => array of rows
                                const chosenHistRows = histData[chosenIID] || [];
                                if (chosenHistRows.length > 0) {
                                    chosenHistRows.forEach((row, idx) => {
                                        const opt = document.createElement(
                                            'option');
                                        opt.value =
                                            idx; // index within the array
                                        opt.textContent = row.ReportName ?
                                            `${row.ReportName} (${row.Year})` :
                                            `Unnamed Report (${row.Year})`;
                                        reportSelect.appendChild(opt);
                                    });
                                    reportSelect.disabled = false;
                                } else {
                                    reportSelect.disabled = true;
                                }
                            }

                            // We can’t move to step 2’s next until user selects a report
                            const step2Next = wizardEl.querySelector(
                                '.wizard-step-content[data-step="2"] .wizard-next-btn'
                            );
                            if (step2Next) {
                                step2Next.disabled = true;
                            }
                        }
                    } else if (currentStep === 2) {
                        // User has selected a past report
                        const chosenIID = indicatorSelect.value;
                        const histData = JSON.parse(indicatorSelect.dataset.hist ||
                            '{}');
                        const chosenHist = histData[chosenIID] || [];

                        // Which index?
                        const rowIndex = reportSelect.value;
                        const chosenRow = chosenHist[rowIndex];
                        if (chosenRow) {
                            if (reportNameEl) reportNameEl.textContent = chosenRow
                                .ReportName ?
                                `${chosenRow.ReportName} (${chosenRow.Year})` :
                                `Unnamed Report (${chosenRow.Year})`;
                            if (responseEl) responseEl.textContent = chosenRow
                                .Response || 'N/A';
                            if (commentEl) commentEl.textContent = chosenRow.Comments ||
                                'N/A';
                            if (reportedByEl) reportedByEl.textContent = chosenRow
                                .ReportedBy || 'N/A';
                        }
                    }

                    goToStep(currentStep + 1);
                });
            });

            // Prev Buttons
            prevBtns.forEach((btn) => {
                btn.addEventListener('click', function() {
                    goToStep(currentStep - 1);
                });
            });

            // Step 2: Once user picks a report => enable "Next"
            if (reportSelect) {
                reportSelect.addEventListener('change', function() {
                    const step2Next = wizardEl.querySelector(
                        '.wizard-step-content[data-step="2"] .wizard-next-btn');
                    if (step2Next) step2Next.disabled = false;
                });
            }

            // Finish
            if (finishBtn) {
                finishBtn.addEventListener('click', function() {
                    // Reset wizard
                    goToStep(1);
                    if (indicatorSelect) indicatorSelect.value = "";
                    if (reportSelect) {
                        reportSelect.innerHTML =
                            `<option value="" disabled selected>Select a Report</option>`;
                        reportSelect.disabled = true;
                    }
                    const step1Next = wizardEl.querySelector(
                        '.wizard-step-content[data-step="1"] .wizard-next-btn');
                    if (step1Next) step1Next.disabled = true;
                    alert("Wizard completed. You may select another indicator if needed.");
                });
            }

            goToStep(1); // default
        });
    });
</script>

<style>
    .table-sm td,
    .table-sm th {
        padding: 0.4rem;
    }

    /* Wizard Step Items */
    .wizard-steps {
        position: relative;
        width: 100%;
        margin-bottom: 1rem;
    }

    .wizard-step-item {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        margin: 0 1rem;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .wizard-step-item.active {
        opacity: 1;
    }

    .wizard-step-number {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        text-align: center;
        line-height: 30px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .wizard-step-label {
        font-size: 13px;
        font-weight: 500;
        color: #333;
    }

    .wizard-step-content {
        display: none;
    }

    .wizard-step-content.show {
        display: block;
    }

    .wizard-step-content label.form-label {
        font-weight: 600;
    }

    .wizard-step-content .border {
        border-color: #dedede !important;
    }

    .wizard-next-btn,
    .wizard-prev-btn,
    .wizard-finish-btn {
        min-width: 100px;
    }
</style>
