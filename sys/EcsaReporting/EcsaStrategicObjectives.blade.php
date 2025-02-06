<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Select Strategic Objective
                </h2>
                <p class="text-muted mt-1">{{ $Desc }}</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('Ecsa_SelectTimeline', ['UserID' => $UserID, 'ClusterID' => $ClusterID]) }}"
                    class="btn btn-ghost-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                    Back to Timeline Selection
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Select a Strategic Objective</h3>
                        <form action="{{ route('Ecsa_ReportPerformanceIndicators') }}" method="GET"
                            id="strategicObjectiveForm">
                            @csrf
                            <input type="hidden" name="UserID" value="{{ $UserID }}">
                            <input type="hidden" name="ClusterID" value="{{ $ClusterID }}">
                            <input type="hidden" name="ReportingID" value="{{ $ReportingID }}">
                            <input type="hidden" name="userName" value="{{ $userName }}">
                            <input type="hidden" name="clusterName" value="{{ $clusterName }}">
                            <input type="hidden" name="timelineName" value="{{ $timelineName }}">
                            <div class="mb-3">
                                <label class="form-label" for="StrategicObjectiveID">Strategic Objective</label>
                                <select class="form-select @error('StrategicObjectiveID') is-invalid @enderror"
                                    id="StrategicObjectiveID" name="StrategicObjectiveID" required
                                    data-bs-toggle="select" data-placeholder="Choose a strategic objective..."
                                    data-allow-clear="true">
                                    <option value="">Select a strategic objective...</option>
                                    @foreach ($strategicObjectives as $objective)
                                        <option value="{{ $objective->StrategicObjectiveID }}"
                                            data-description="{{ $objective->Description }}"
                                            {{ old('StrategicObjectiveID') == $objective->StrategicObjectiveID ? 'selected' : '' }}>
                                            {{ $objective->SO_Number }} - {{ $objective->SO_Name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('StrategicObjectiveID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="objectiveDescription" class="alert alert-info d-none mt-3" role="alert">
                                <h4 class="alert-heading">Objective Description</h4>
                                <p class="mb-0"></p>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-target-arrow" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 7a5 5 0 1 0 5 5"></path>
                                        <path d="M13 3.055a9 9 0 1 0 7.941 7.945"></path>
                                        <path d="M15 6v3h3l3 -3h-3v-3z"></path>
                                        <path d="M15 9l-3 3"></path>
                                    </svg>
                                    Continue to Performance Indicators
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card bg-primary-subtle">
                            <div class="card-body">
                                <h3 class="card-title text-primary">Why Select a Strategic Objective?</h3>
                                <p class="text-muted">Choosing a strategic objective allows you to:</p>
                                <ul class="list-unstyled space-y-1">
                                    <li class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-check text-primary" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                        <span class="ms-2">Focus on specific organizational goals</span>
                                    </li>
                                    <li class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-check text-primary" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                        <span class="ms-2">Align reporting with strategic priorities</span>
                                    </li>
                                    <li class="d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-check text-primary" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                        <span class="ms-2">Track progress towards key objectives</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card bg-yellow-subtle">
                            <div class="card-body">
                                <h3 class="card-title text-yellow">Reporting Context</h3>
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Selected User</div>
                                        <div class="datagrid-content">{{ $userName }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Selected Cluster</div>
                                        <div class="datagrid-content">{{ $clusterName }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Reporting Timeline</div>
                                        <div class="datagrid-content">{{ $timelineName }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('StrategicObjectiveID'), {
            copyClassesToDropdown: false,
            dropdownParent: 'body',
            controlInput: '<input>',
            render: {
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
            onChange: function(value) {
                var description = this.getOption(value).dataset.description;
                var descriptionElement = document.getElementById('objectiveDescription');
                var submitBtn = document.getElementById('submitBtn');

                if (description) {
                    descriptionElement.querySelector('p').textContent = description;
                    descriptionElement.classList.remove('d-none');
                } else {
                    descriptionElement.classList.add('d-none');
                }

                submitBtn.disabled = !value;
            }
        }));
    });
</script>
