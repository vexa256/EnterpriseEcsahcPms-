<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Performance Indicator Reporting</h2>
                    <div class="text-muted mt-1">{{ $objectiveName }}</div>
                    <div class="text-muted mt-1">
                        Timeline: {{ $timelineName }} | Cluster: {{ $clusterName }} | User: {{ $userName }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <!-- Progress Overview -->
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">Reporting Progress</h3>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-primary" style="width: {{ $progressPercentage }}%"
                            role="progressbar" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0"
                            aria-valuemax="100">
                            <span class="visually-hidden">{{ number_format($progressPercentage, 1) }}% Complete</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ $reportedIndicators }} of {{ $totalIndicators }} indicators reported</span>
                        <span>{{ number_format($progressPercentage, 1) }}% Complete</span>
                    </div>
                </div>
            </div>

            <!-- Indicators List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performance Indicators</h3>
                </div>
                <div class="card-body">
                    <form id="bulkNotApplicableForm" action="{{ route('MarkIndicatorsNotApplicable') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="UserID" value="{{ $UserID }}">
                        <input type="hidden" name="ClusterID" value="{{ $ClusterID }}">
                        <input type="hidden" name="ReportingID" value="{{ $ReportingID }}">
                        <input type="hidden" name="StrategicObjectiveID" value="{{ $StrategicObjectiveID }}">


                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </th>
                                        <th width="10%">Number</th>
                                        <th width="45%">Indicator</th>
                                        <th width="15%">Status</th>
                                        <th width="25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($indicators as $indicator)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input indicator-checkbox"
                                                    name="IndicatorIDs[]" value="{{ $indicator->id }}"
                                                    {{ isset($existingReports[$indicator->id]) && $existingReports[$indicator->id]->Response === 'Not Applicable' ? 'disabled' : '' }}>
                                            </td>
                                            <td>{{ $indicator->Indicator_Number }}</td>
                                            <td>{{ $indicator->Indicator_Name }}</td>
                                            <td>
                                                @if (isset($existingReports[$indicator->id]))
                                                    @if ($existingReports[$indicator->id]->Response === 'Not Applicable')
                                                        <span class="badge bg-secondary">Not Applicable</span>
                                                    @else
                                                        <span class="badge bg-success">Reported</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#reportModal"
                                                    data-indicator-id="{{ $indicator->id }}"
                                                    data-indicator-name="{{ $indicator->Indicator_Name }}"
                                                    data-indicator-number="{{ $indicator->Indicator_Number }}"
                                                    data-response-type="{{ $indicator->ResponseType }}"
                                                    data-existing-response="{{ isset($existingReports[$indicator->id]) ? $existingReports[$indicator->id]->Response : '' }}"
                                                    data-existing-comment="{{ isset($existingReports[$indicator->id]) ? $existingReports[$indicator->id]->ReportingComment : '' }}">
                                                    {{ isset($existingReports[$indicator->id]) ? 'Edit Report' : 'Report' }}
                                                </button>
                                                @if (isset($existingReports[$indicator->id]))
                                                    <button type="button" class="btn btn-secondary btn-sm ms-2"
                                                        data-bs-toggle="modal" data-bs-target="#detailsModal"
                                                        data-indicator-id="{{ $indicator->id }}"
                                                        data-indicator-name="{{ $indicator->Indicator_Name }}"
                                                        data-indicator-number="{{ $indicator->Indicator_Number }}"
                                                        data-response-type="{{ $indicator->ResponseType }}"
                                                        data-existing-response="{{ $existingReports[$indicator->id]->Response }}"
                                                        data-existing-comment="{{ $existingReports[$indicator->id]->ReportingComment }}"
                                                        data-reporter-name="{{ $existingReports[$indicator->id]->reporter_name }}"
                                                        data-reporter-email="{{ $existingReports[$indicator->id]->reporter_email }}"
                                                        data-reported-at="{{ $existingReports[$indicator->id]->updated_at }}">
                                                        View Details
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Reporting Modal -->
<div class="modal modal-blur fade modal-xl" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('Ecsa_SavePerformanceReport') }}" method="POST">
                @csrf
                <input type="hidden" name="UserID" value="{{ $UserID }}">
                <input type="hidden" name="ClusterID" value="{{ $ClusterID }}">
                <input type="hidden" name="ReportingID" value="{{ $ReportingID }}">
                <input type="hidden" name="StrategicObjectiveID" value="{{ $StrategicObjectiveID }}">
                <input type="hidden" name="IndicatorID" id="modalIndicatorID">
                <input type="hidden" name="ResponseType" id="modalResponseType">

                <div class="modal-body">
                    <div class="container-xl">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 id="modalIndicatorName" class="card-title mb-4"></h3>
                                        <div class="mb-4">
                                            <label class="form-label">Response</label>
                                            <div id="responseInputContainer" class="mb-3"></div>
                                        </div>
                                        <div>
                                            <label class="form-label">Comment</label>
                                            <textarea class="form-control" name="Comment" rows="4" id="modalComment"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Indicator Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <p id="modalIndicatorNumber" class="text-muted mb-3"></p>
                                        <div class="mb-2">Response Type: <span style="color:white"
                                                id="modalResponseTypeDisplay" class="badge bg-blue ms-2"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary ms-auto">Save Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal modal-blur fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Indicator Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 id="detailsIndicatorName" class="mb-3"></h3>
                <p id="detailsIndicatorNumber" class="text-muted mb-3"></p>
                <div class="mb-3">
                    <strong>Response Type:</strong> <span id="detailsResponseType"></span>
                </div>
                <div class="mb-3">
                    <strong>Response:</strong>
                    <p id="detailsResponse"></p>
                </div>
                <div class="mb-3">
                    <strong>Comment:</strong>
                    <p id="detailsComment"></p>
                </div>
                <div class="mb-3">
                    <strong>Reported By:</strong>
                    <p id="detailsReporterName"></p>
                </div>
                <div class="mb-3">
                    <strong>Reporter Email:</strong>
                    <p id="detailsReporterEmail"></p>
                </div>
                <div>
                    <strong>Reported At:</strong>
                    <p id="detailsReportedAt"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Checkbox handling
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.indicator-checkbox:not([disabled])');
        const markNotApplicableBtn = document.getElementById('markNotApplicableBtn');

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateButtonState();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonState);
        });

        function updateButtonState() {
            const checkedBoxes = document.querySelectorAll('.indicator-checkbox:checked');
            markNotApplicableBtn.disabled = checkedBoxes.length === 0;

            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            const someChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        }

        // Modal handling
        const reportModal = document.getElementById('reportModal');
        reportModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const indicatorId = button.getAttribute('data-indicator-id');
            const indicatorName = button.getAttribute('data-indicator-name');
            const indicatorNumber = button.getAttribute('data-indicator-number');
            const responseType = button.getAttribute('data-response-type');
            const existingResponse = button.getAttribute('data-existing-response');
            const existingComment = button.getAttribute('data-existing-comment');

            const modalTitle = reportModal.querySelector('.modal-title');
            const modalIndicatorName = reportModal.querySelector('#modalIndicatorName');
            const modalIndicatorNumber = reportModal.querySelector('#modalIndicatorNumber');
            const modalIndicatorID = reportModal.querySelector('#modalIndicatorID');
            const modalResponseType = reportModal.querySelector('#modalResponseType');
            const modalResponseTypeDisplay = reportModal.querySelector('#modalResponseTypeDisplay');
            const modalComment = reportModal.querySelector('#modalComment');
            const responseInputContainer = reportModal.querySelector('#responseInputContainer');

            modalTitle.textContent = `Report Indicator ${indicatorNumber}`;
            modalIndicatorName.textContent = indicatorName;
            modalIndicatorNumber.textContent = `Indicator Number: ${indicatorNumber}`;
            modalIndicatorID.value = indicatorId;
            modalResponseType.value = responseType;
            modalResponseTypeDisplay.textContent = responseType;
            modalComment.value = existingComment || '';

            // Clear previous content
            responseInputContainer.innerHTML = '';

            // Create input based on response type
            let inputElement;
            switch (responseType) {
                case 'Text':
                    inputElement = document.createElement('textarea');
                    inputElement.className = 'form-control';
                    inputElement.name = 'Response';
                    inputElement.rows = '4';
                    break;
                case 'Number':
                    inputElement = document.createElement('input');
                    inputElement.type = 'number';
                    inputElement.className = 'form-control';
                    inputElement.name = 'Response';
                    break;
                case 'Boolean':
                case 'Yes/No':
                    inputElement = document.createElement('select');
                    inputElement.className = 'form-select';
                    inputElement.name = 'Response';
                    const options = responseType === 'Boolean' ? ['True', 'False'] : ['Yes', 'No'];
                    options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option;
                        optionElement.textContent = option;
                        inputElement.appendChild(optionElement);
                    });
                    break;
                default:
                    inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.className = 'form-control';
                    inputElement.name = 'Response';
            }

            if (existingResponse) {
                inputElement.value = existingResponse;
            }

            responseInputContainer.appendChild(inputElement);
        });

        // Details Modal handling
        const detailsModal = document.getElementById('detailsModal');
        detailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const indicatorName = button.getAttribute('data-indicator-name');
            const indicatorNumber = button.getAttribute('data-indicator-number');
            const responseType = button.getAttribute('data-response-type');
            const existingResponse = button.getAttribute('data-existing-response');
            const existingComment = button.getAttribute('data-existing-comment');
            const reporterName = button.getAttribute('data-reporter-name');
            const reporterEmail = button.getAttribute('data-reporter-email');
            const reportedAt = button.getAttribute('data-reported-at');

            const modalIndicatorName = detailsModal.querySelector('#detailsIndicatorName');
            const modalIndicatorNumber = detailsModal.querySelector('#detailsIndicatorNumber');
            const modalResponseType = detailsModal.querySelector('#detailsResponseType');
            const modalResponse = detailsModal.querySelector('#detailsResponse');
            const modalComment = detailsModal.querySelector('#detailsComment');
            const modalReporterName = detailsModal.querySelector('#detailsReporterName');
            const modalReporterEmail = detailsModal.querySelector('#detailsReporterEmail');
            const modalReportedAt = detailsModal.querySelector('#detailsReportedAt');

            modalIndicatorName.textContent = indicatorName;
            modalIndicatorNumber.textContent = `Indicator Number: ${indicatorNumber}`;
            modalResponseType.textContent = responseType;
            modalResponse.textContent = existingResponse;
            modalComment.textContent = existingComment || 'No comment provided';
            modalReporterName.textContent = reporterName;
            modalReporterEmail.textContent = reporterEmail;
            modalReportedAt.textContent = new Date(reportedAt).toLocaleString();
        });
    });
</script>
