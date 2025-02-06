<!-- Page Header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <!-- Button to open "Add New RRF Indicator" modal -->
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addRrfIndicatorModal">
                        <i class="fas fa-plus"></i>
                        Add New RRF Indicator
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Body -->
<div class="page-body">
    <div class="container-xl">
        <!-- Indicators Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table tableme">
                    <thead>
                        <tr>
                            <!-- We omit IID in the table for brevity -->
                            <th>Indicator</th>
                            <th>Reporting Period</th>
                            <th>Baseline 2024</th>
                            <th>Target 2025</th>
                            <th>Target 2026</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indicators as $indicator)
                            <tr>
                                <td>{{ $indicator->Indicator }}</td>
                                <td>{{ $indicator->ReportingPeriod }}</td>
                                <td>{{ $indicator->Baseline2024 }}</td>
                                <td>{{ $indicator->TargetYearTwo2025 }}</td>
                                <td>{{ $indicator->TargetYearThree2026 }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editRrfIndicatorModal-{{ $indicator->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <!-- "View More" Button -->
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewMoreRrfModal-{{ $indicator->id }}">
                                            <i class="fas fa-eye"></i> View More
                                        </button>

                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $indicator->id }}"
                                            action="{{ route('mpaRRF.DeleteRRFIndicator') }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $indicator->id }}">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $indicator->id }}')">
                                                <i class="fas fa-trash"></i> Trash
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add New RRF Indicator Modal (Full Screen) -->
<div class="modal fade" id="addRrfIndicatorModal" aria-labelledby="addRrfIndicatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addRrfIndicatorModalLabel">
                    Add New RRF Indicator
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @php
                    // Generate a hidden IID: date(now) + uniqid => MD5
                    $generatedIID = md5(uniqid(now(), true));
                @endphp

                <form action="{{ route('mpaRRF.StoreRRFIndicator') }}" method="POST" id="addRrfIndicatorForm"
                    class="row g-3">
                    @csrf

                    <!-- Permanently set EntityID to 'RRF' -->
                    <input type="hidden" name="EntityID" value="RRF">
                    <!-- Permanently set Primary Category to 'RRF' -->
                    <input type="hidden" name="IndicatorPrimaryCategory" value="RRF">
                    <!-- Auto-generated hidden IID -->
                    <input type="hidden" name="IID" value="{{ $generatedIID }}">

                    <!-- Indicator Secondary Category -->
                    <div class="col-md-4">
                        <label class="form-label">Secondary Category</label>
                        <select class="form-select" name="IndicatorSecondaryCategory" required>
                            <option value="" disabled selected>Select Secondary Category</option>
                            <option value="Project Development Objective (PDO) indicators">
                                RRF Project Development Objective (PDO) indicators
                            </option>
                            <option value="Intermediate Results Indicators (IRI)">
                                RRF Intermediate Results Indicators (IRI)
                            </option>
                        </select>
                    </div>

                    <!-- Indicator -->
                    <div class="col-md-4">
                        <label class="form-label">Indicator</label>
                        <input type="text" class="form-control" name="Indicator" required>
                    </div>

                    <!-- IndicatorDefinition -->
                    <div class="col-md-4">
                        <label class="form-label">Definition</label>
                        <textarea class="form-control" name="IndicatorDefinition" rows="2"></textarea>
                    </div>

                    <!-- IndicatorQuestion -->
                    <div class="col-md-4">
                        <label class="form-label">Question</label>
                        <textarea class="form-control" name="IndicatorQuestion" rows="2"></textarea>
                    </div>

                    <!-- RemarksComments -->
                    <div class="col-md-4">
                        <label class="form-label">Remarks / Comments</label>
                        <textarea class="form-control" name="RemarksComments" rows="2"></textarea>
                    </div>

                    <!-- SourceOfData -->
                    <div class="col-md-4">
                        <label class="form-label">Source of Data</label>
                        <input type="text" class="form-control" name="SourceOfData">
                    </div>

                    <!-- ResponseType -->
                    <div class="col-md-4">
                        <label class="form-label">Response Type</label>
                        <select class="form-select" name="ResponseType" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Text">Text</option>
                            <option value="Number">Number</option>
                            <option value="Boolean">Boolean</option>
                            <option value="Yes/No">Yes/No</option>
                        </select>
                    </div>

                    <!-- ReportingPeriod -->
                    <div class="col-md-4">
                        <label class="form-label">Reporting Period</label>
                        <select class="form-select" name="ReportingPeriod">
                            <option value="" disabled selected>Select Period</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Bi-Annual">Bi-Annual</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>

                    <!-- ExpectedTarget -->
                    <div class="col-md-4">
                        <label class="form-label">Expected Target</label>
                        <input type="text" class="form-control" name="ExpectedTarget">
                    </div>

                    <!-- BaselinePAD2023 -->
                    <div class="col-md-4">
                        <label class="form-label">Baseline PAD 2023</label>
                        <input type="text" class="form-control" name="BaselinePAD2023">
                    </div>

                    <!-- Baseline2024 -->
                    <div class="col-md-4">
                        <label class="form-label">Baseline 2024</label>
                        <input type="text" class="form-control" name="Baseline2024">
                    </div>

                    <!-- TargetYearOne2024 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2024 (Year 1)</label>
                        <input type="text" class="form-control" name="TargetYearOne2024">
                    </div>

                    <!-- TargetYearTwo2025 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2025 (Year 2)</label>
                        <input type="text" class="form-control" name="TargetYearTwo2025">
                    </div>

                    <!-- TargetYearThree2026 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2026 (Year 3)</label>
                        <input type="text" class="form-control" name="TargetYearThree2026">
                    </div>

                    <!-- TargetYearFour2027 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2027 (Year 4)</label>
                        <input type="text" class="form-control" name="TargetYearFour2027">
                    </div>

                    <!-- TargetYearFive2028 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2028 (Year 5)</label>
                        <input type="text" class="form-control" name="TargetYearFive2028">
                    </div>

                    <!-- TargetYearSix2029 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2029 (Year 6)</label>
                        <input type="text" class="form-control" name="TargetYearSix2029">
                    </div>

                    <!-- TargetYearSeven2030 -->
                    <div class="col-md-4">
                        <label class="form-label">Target 2030 (Year 7)</label>
                        <input type="text" class="form-control" name="TargetYearSeven2030">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" form="addRrfIndicatorForm" class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit RRF Indicator Modals (Hidden) -->
@foreach ($indicators as $indicator)
    <div class="modal fade" id="editRrfIndicatorModal-{{ $indicator->id }}" tabindex="-1"
        aria-labelledby="editRrfIndicatorModalLabel-{{ $indicator->id }}" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content border-0 shadow-xl">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRrfIndicatorModalLabel-{{ $indicator->id }}">
                        Edit RRF Indicator (ID: {{ $indicator->id }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mpaRRF.UpdateRRFIndicator') }}" method="POST"
                        id="editRrfIndicatorForm-{{ $indicator->id }}" class="row g-3">
                        @csrf
                        @method('PUT')

                        <!-- Primary Key -->
                        <input type="hidden" name="id" value="{{ $indicator->id }}">
                        <!-- Hardcode EntityID = 'RRF' so we do not allow changes -->
                        <input type="hidden" name="EntityID" value="RRF">
                        <!-- Hardcode PrimaryCategory = 'RRF' so we do not allow changes -->
                        <input type="hidden" name="IndicatorPrimaryCategory" value="RRF">
                        <!-- Keep the existing IID as well -->
                        <input type="hidden" name="IID" value="{{ $indicator->IID }}">

                        <!-- Indicator Secondary Category -->
                        <div class="col-md-4">
                            <label class="form-label">Secondary Category</label>
                            <select class="form-select" name="IndicatorSecondaryCategory" required>
                                <option value="Project Development Objective (PDO) indicators"
                                    @if ($indicator->IndicatorSecondaryCategory === 'Project Development Objective (PDO) indicators') selected @endif>
                                    Project Development Objective (PDO) indicators
                                </option>
                                <option value="Intermediate Results Indicators (IRI)"
                                    @if ($indicator->IndicatorSecondaryCategory === 'Intermediate Results Indicators (IRI)') selected @endif>
                                    Intermediate Results Indicators (IRI)
                                </option>
                            </select>
                        </div>

                        <!-- Indicator -->
                        <div class="col-md-4">
                            <label class="form-label">Indicator</label>
                            <input type="text" class="form-control" name="Indicator"
                                value="{{ $indicator->Indicator }}" required>
                        </div>

                        <!-- IndicatorDefinition -->
                        <div class="col-md-4">
                            <label class="form-label">Definition</label>
                            <textarea class="form-control" name="IndicatorDefinition" rows="2">{{ $indicator->IndicatorDefinition }}</textarea>
                        </div>

                        <!-- IndicatorQuestion -->
                        <div class="col-md-4">
                            <label class="form-label">Question</label>
                            <textarea class="form-control" name="IndicatorQuestion" rows="2">{{ $indicator->IndicatorQuestion }}</textarea>
                        </div>

                        <!-- RemarksComments -->
                        <div class="col-md-4">
                            <label class="form-label">Remarks / Comments</label>
                            <textarea class="form-control" name="RemarksComments" rows="2">{{ $indicator->RemarksComments }}</textarea>
                        </div>

                        <!-- SourceOfData -->
                        <div class="col-md-4">
                            <label class="form-label">Source Of Data</label>
                            <input type="text" class="form-control" name="SourceOfData"
                                value="{{ $indicator->SourceOfData }}">
                        </div>

                        <!-- ResponseType -->
                        <div class="col-md-4">
                            <label class="form-label">Response Type</label>
                            <select class="form-select" name="ResponseType" required>
                                <option value="Text" @if ($indicator->ResponseType === 'Text') selected @endif>Text</option>
                                <option value="Number" @if ($indicator->ResponseType === 'Number') selected @endif>Number
                                </option>
                                <option value="Boolean" @if ($indicator->ResponseType === 'Boolean') selected @endif>Boolean
                                </option>
                                <option value="Yes/No" @if ($indicator->ResponseType === 'Yes/No') selected @endif>Yes/No
                                </option>
                            </select>
                        </div>

                        <!-- ReportingPeriod -->
                        <div class="col-md-4">
                            <label class="form-label">Reporting Period</label>
                            <select class="form-select" name="ReportingPeriod">
                                <option value="" disabled>Choose Period</option>
                                <option value="Quarterly" @if ($indicator->ReportingPeriod === 'Quarterly') selected @endif>Quarterly
                                </option>
                                <option value="Bi-Annual" @if ($indicator->ReportingPeriod === 'Bi-Annual') selected @endif>Bi-Annual
                                </option>
                                <option value="Annual" @if ($indicator->ReportingPeriod === 'Annual') selected @endif>Annual
                                </option>
                            </select>
                        </div>

                        <!-- ExpectedTarget -->
                        <div class="col-md-4">
                            <label class="form-label">Expected Target</label>
                            <input type="text" class="form-control" name="ExpectedTarget"
                                value="{{ $indicator->ExpectedTarget }}">
                        </div>

                        <!-- BaselinePAD2023 -->
                        <div class="col-md-4">
                            <label class="form-label">Baseline PAD 2023</label>
                            <input type="text" class="form-control" name="BaselinePAD2023"
                                value="{{ $indicator->BaselinePAD2023 }}">
                        </div>

                        <!-- Baseline2024 -->
                        <div class="col-md-4">
                            <label class="form-label">Baseline 2024</label>
                            <input type="text" class="form-control" name="Baseline2024"
                                value="{{ $indicator->Baseline2024 }}">
                        </div>

                        <!-- TargetYearOne2024 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2024 (Year 1)</label>
                            <input type="text" class="form-control" name="TargetYearOne2024"
                                value="{{ $indicator->TargetYearOne2024 }}">
                        </div>

                        <!-- TargetYearTwo2025 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2025 (Year 2)</label>
                            <input type="text" class="form-control" name="TargetYearTwo2025"
                                value="{{ $indicator->TargetYearTwo2025 }}">
                        </div>

                        <!-- TargetYearThree2026 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2026 (Year 3)</label>
                            <input type="text" class="form-control" name="TargetYearThree2026"
                                value="{{ $indicator->TargetYearThree2026 }}">
                        </div>

                        <!-- TargetYearFour2027 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2027 (Year 4)</label>
                            <input type="text" class="form-control" name="TargetYearFour2027"
                                value="{{ $indicator->TargetYearFour2027 }}">
                        </div>

                        <!-- TargetYearFive2028 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2028 (Year 5)</label>
                            <input type="text" class="form-control" name="TargetYearFive2028"
                                value="{{ $indicator->TargetYearFive2028 }}">
                        </div>

                        <!-- TargetYearSix2029 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2029 (Year 6)</label>
                            <input type="text" class="form-control" name="TargetYearSix2029"
                                value="{{ $indicator->TargetYearSix2029 }}">
                        </div>

                        <!-- TargetYearSeven2030 -->
                        <div class="col-md-4">
                            <label class="form-label">Target 2030 (Year 7)</label>
                            <input type="text" class="form-control" name="TargetYearSeven2030"
                                value="{{ $indicator->TargetYearSeven2030 }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" form="editRrfIndicatorForm-{{ $indicator->id }}" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- "View More" RRF Modals (Hidden) -->
@foreach ($indicators as $indicator)
    <div class="modal fade" id="viewMoreRrfModal-{{ $indicator->id }}" tabindex="-1"
        aria-labelledby="viewMoreRrfModalLabel-{{ $indicator->id }}" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content border-0 shadow-xl">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewMoreRrfModalLabel-{{ $indicator->id }}">
                        RRF Indicator Full Details (ID: {{ $indicator->id }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Read-only display of ALL columns -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Primary Category</th>
                                    <td>{{ $indicator->IndicatorPrimaryCategory }}</td>
                                </tr>
                                <tr>
                                    <th>Secondary Category</th>
                                    <td>{{ $indicator->IndicatorSecondaryCategory }}</td>
                                </tr>
                                <tr>
                                    <th>Indicator</th>
                                    <td>{{ $indicator->Indicator }}</td>
                                </tr>
                                <tr>
                                    <th>Definition</th>
                                    <td>{{ $indicator->IndicatorDefinition }}</td>
                                </tr>
                                <tr>
                                    <th>Question</th>
                                    <td>{{ $indicator->IndicatorQuestion }}</td>
                                </tr>
                                <tr>
                                    <th>Remarks / Comments</th>
                                    <td>{{ $indicator->RemarksComments }}</td>
                                </tr>
                                <tr>
                                    <th>Source Of Data</th>
                                    <td>{{ $indicator->SourceOfData }}</td>
                                </tr>
                                <tr>
                                    <th>Response Type</th>
                                    <td>{{ $indicator->ResponseType }}</td>
                                </tr>
                                <tr>
                                    <th>Reporting Period</th>
                                    <td>{{ $indicator->ReportingPeriod }}</td>
                                </tr>
                                <tr>
                                    <th>Expected Target</th>
                                    <td>{{ $indicator->ExpectedTarget }}</td>
                                </tr>
                                <tr>
                                    <th>Baseline PAD 2023</th>
                                    <td>{{ $indicator->BaselinePAD2023 }}</td>
                                </tr>
                                <tr>
                                    <th>Baseline 2024</th>
                                    <td>{{ $indicator->Baseline2024 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 1 (2024)</th>
                                    <td>{{ $indicator->TargetYearOne2024 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 2 (2025)</th>
                                    <td>{{ $indicator->TargetYearTwo2025 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 3 (2026)</th>
                                    <td>{{ $indicator->TargetYearThree2026 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 4 (2027)</th>
                                    <td>{{ $indicator->TargetYearFour2027 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 5 (2028)</th>
                                    <td>{{ $indicator->TargetYearFive2028 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 6 (2029)</th>
                                    <td>{{ $indicator->TargetYearSix2029 }}</td>
                                </tr>
                                <tr>
                                    <th>Target Year 7 (2030)</th>
                                    <td>{{ $indicator->TargetYearSeven2030 }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $indicator->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $indicator->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- SweetAlert2 Script for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(indicatorId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + indicatorId).submit();
            }
        });
    }
</script>
