<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        {{ $SelectedEntity->Entity }} Indicators Management
                    </h2>
                    <p class="text-muted mt-1">Manage and track {{ $SelectedEntity->Entity }} indicators.</p>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#addRrfIndicatorModal">
                            <i class="fas fa-plus me-2"></i>
                            Add New {{ $SelectedEntity->Entity }} Indicator
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#addRrfIndicatorModal"
                            aria-label="Add New {{ $SelectedEntity->Entity }} Indicator">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div><i class="fas fa-check me-2"></i></div>
                        <div>{{ session('success') }}</div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div><i class="fas fa-exclamation-triangle me-2"></i></div>
                        <div>{{ session('error') }}</div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $SelectedEntity->Entity }} Indicators</h3>
                    <div class="card-actions">
                        <input type="text" class="form-control" placeholder="Search indicators..."
                            id="indicatorSearch">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped" id="indicatorsTable">
                            <thead>
                                <tr>
                                    <th>Indicator</th>
                                    <th>Category</th>
                                    <th>Reporting Period</th>
                                    <th>Baseline 2024</th>
                                    <th>Target 2030</th>
                                    <th class="w-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indicators as $indicator)
                                    <tr>
                                        <td>{{ $indicator->Indicator }}</td>
                                        <td>{{ $indicator->SecondaryCategory }}</td>
                                        <td>{{ $indicator->ReportingPeriod }}</td>
                                        <td>{{ $indicator->Baseline2024 }}</td>
                                        <td>{{ $indicator->TargetYearSeven2030 }}</td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <button class="btn btn-icon btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editRrfIndicatorModal-{{ $indicator->id }}"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-icon btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#viewMoreRrfModal-{{ $indicator->id }}"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <form id="delete-form-{{ $indicator->id }}"
                                                    action="{{ route('mpaRRF.DeleteRRFIndicator') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $indicator->id }}">
                                                    <button type="button" class="btn btn-icon btn-danger"
                                                        onclick="confirmDelete('{{ $indicator->id }}')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            <div class="empty">
                                                <div class="empty-img"><img
                                                        src="{{ asset('static/illustrations/undraw_printing_invoices_5r4r.svg') }}"
                                                        height="128" alt="">
                                                </div>
                                                <p class="empty-title">No indicators found</p>
                                                <p class="empty-subtitle text-muted">
                                                    Start by adding a new {{ $SelectedEntity->Entity }} indicator using
                                                    the button above.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New RRF Indicator Modal -->
<div class="modal modal-blur fade" id="addRrfIndicatorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New {{ $SelectedEntity->Entity }} Indicator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mpaRRF.StoreRRFIndicator') }}" method="POST" id="addRrfIndicatorForm">
                    @csrf
                    <input type="hidden" name="EntityID" value="{{ $SelectedEntity->EntityID }}">
                    <input type="hidden" name="IID" value="{{ md5(uniqid(now(), true)) }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Primary Category</label>
                            <input type="text" class="form-control" name="IndicatorPrimaryCategory"
                                value="{{ $SelectedEntity->EntityID }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Secondary Category</label>
                            <select class="form-select" name="IndicatorSecondaryCategory" required>
                                <option value="" disabled selected>Select Secondary Category</option>
                                <option value="{{ $SelectedEntity->EntityID }} PDO">{{ $SelectedEntity->EntityID }}
                                    Project Development Objective (PDO) indicators</option>
                                <option value="{{ $SelectedEntity->EntityID }} Intermediate">
                                    {{ $SelectedEntity->EntityID }} Intermediate Results Indicators (IRI)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Indicator</label>
                            <input type="text" class="form-control" name="Indicator" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Indicator Definition</label>
                            <textarea class="form-control" name="IndicatorDefinition" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Indicator Question</label>
                            <textarea class="form-control" name="IndicatorQuestion" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Source of Data</label>
                            <input type="text" class="form-control" name="SourceOfData">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Response Type</label>
                            <select class="form-select" name="ResponseType" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Text">Text</option>
                                <option value="Number">Number</option>
                                <option value="Boolean">Boolean</option>
                                <option value="Percentage">Percentage</option>
                                <option value="Yes/No">Yes/No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reporting Period</label>
                            <input type="text" class="form-control" name="ReportingPeriod"
                                placeholder="e.g., 2023-2030">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Target</label>
                            <input type="text" class="form-control" name="ExpectedTarget">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Baseline PAD 2023</label>
                            <input type="text" class="form-control" name="BaselinePAD2023">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Baseline 2024</label>
                            <input type="text" class="form-control" name="Baseline2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2024 (Year 1)</label>
                            <input type="text" class="form-control" name="TargetYearOne2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2025 (Year 2)</label>
                            <input type="text" class="form-control" name="TargetYearTwo2025">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2026 (Year 3)</label>
                            <input type="text" class="form-control" name="TargetYearThree2026">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2027 (Year 4)</label>
                            <input type="text" class="form-control" name="TargetYearFour2027">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2028 (Year 5)</label>
                            <input type="text" class="form-control" name="TargetYearFive2028">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2029 (Year 6)</label>
                            <input type="text" class="form-control" name="TargetYearSix2029">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target 2030 (Year 7)</label>
                            <input type="text" class="form-control" name="TargetYearSeven2030">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remarks / Comments</label>
                            <textarea class="form-control" name="RemarksComments" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addRrfIndicatorForm" class="btn btn-primary ms-auto">
                    <i class="fas fa-save me-2"></i>Save Indicator
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit RRF Indicator Modals -->
@foreach ($indicators as $indicator)
    <div class="modal modal-blur fade" id="editRrfIndicatorModal-{{ $indicator->id }}" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit {{ $SelectedEntity->Entity }} Indicator (ID: {{ $indicator->id }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mpaRRF.UpdateRRFIndicator') }}" method="POST"
                        id="editRrfIndicatorForm-{{ $indicator->id }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $indicator->id }}">
                        <input type="hidden" name="EntityID" value="{{ $SelectedEntity->EntityID }}">
                        <input type="hidden" name="IID" value="{{ $indicator->IID }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Primary Category</label>
                                <input type="text" class="form-control" name="IndicatorPrimaryCategory"
                                    value="{{ $indicator->PrimaryCategory }}" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secondary Category</label>
                                <select class="form-select" name="IndicatorSecondaryCategory" required>
                                    <option value="{{ $SelectedEntity->EntityID }} PDO"
                                        {{ $indicator->SecondaryCategory == $SelectedEntity->EntityID }} PDO'
                                        ? 'selected' : '' }}>{{ $SelectedEntity->EntityID }} Project Development
                                        Objective (PDO) indicators</option>
                                    <option value="{{ $SelectedEntity->EntityID }} Intermediate"
                                        {{ $indicator->SecondaryCategory == $SelectedEntity->EntityID }} Intermediate'
                                        ? 'selected' : '' }}>{{ $SelectedEntity->EntityID }} Intermediate Results
                                        Indicators (IRI)</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Indicator</label>
                                <input type="text" class="form-control" name="Indicator"
                                    value="{{ $indicator->Indicator }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Indicator Definition</label>
                                <textarea class="form-control" name="IndicatorDefinition" rows="3">{{ $indicator->IndicatorDefinition }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Indicator Question</label>
                                <textarea class="form-control" name="IndicatorQuestion" rows="3">{{ $indicator->IndicatorQuestion }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Source of Data</label>
                                <input type="text" class="form-control" name="SourceOfData"
                                    value="{{ $indicator->SourceOfData }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Response Type</label>
                                <select class="form-select" name="ResponseType" required>
                                    <option value="Text" {{ $indicator->ResponseType == 'Text' ? 'selected' : '' }}>
                                        Text</option>
                                    <option value="Number"
                                        {{ $indicator->ResponseType == 'Number' ? 'selected' : '' }}>Number</option>
                                    <option value="Boolean"
                                        {{ $indicator->ResponseType == 'Boolean' ? 'selected' : '' }}>Boolean</option>
                                    <option value="Percentage"
                                        {{ $indicator->ResponseType == 'Percentage' ? 'selected' : '' }}>Percentage
                                    </option>
                                    <option value="Yes/No"
                                        {{ $indicator->ResponseType == 'Yes/No' ? 'selected' : '' }}>Yes/No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reporting Period</label>
                                <input type="text" class="form-control" name="ReportingPeriod"
                                    value="{{ $indicator->ReportingPeriod }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Expected Target</label>
                                <input type="text" class="form-control" name="ExpectedTarget"
                                    value="{{ $indicator->ExpectedTarget }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Baseline PAD 2023</label>
                                <input type="text" class="form-control" name="BaselinePAD2023"
                                    value="{{ $indicator->BaselinePAD2023 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Baseline 2024</label>
                                <input type="text" class="form-control" name="Baseline2024"
                                    value="{{ $indicator->Baseline2024 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2024 (Year 1)</label>
                                <input type="text" class="form-control" name="TargetYearOne2024"
                                    value="{{ $indicator->TargetYearOne2024 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2025 (Year 2)</label>
                                <input type="text" class="form-control" name="TargetYearTwo2025"
                                    value="{{ $indicator->TargetYearTwo2025 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2026 (Year 3)</label>
                                <input type="text" class="form-control" name="TargetYearThree2026"
                                    value="{{ $indicator->TargetYearThree2026 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2027 (Year 4)</label>
                                <input type="text" class="form-control" name="TargetYearFour2027"
                                    value="{{ $indicator->TargetYearFour2027 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2028 (Year 5)</label>
                                <input type="text" class="form-control" name="TargetYearFive2028"
                                    value="{{ $indicator->TargetYearFive2028 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2029 (Year 6)</label>
                                <input type="text" class="form-control" name="TargetYearSix2029"
                                    value="{{ $indicator->TargetYearSix2029 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Target 2030 (Year 7)</label>
                                <input type="text" class="form-control" name="TargetYearSeven2030"
                                    value="{{ $indicator->TargetYearSeven2030 }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks / Comments</label>
                                <textarea class="form-control" name="RemarksComments" rows="3">{{ $indicator->RemarksComments }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editRrfIndicatorForm-{{ $indicator->id }}"
                        class="btn btn-primary ms-auto">
                        <i class="fas fa-save me-2"></i>Update Indicator
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- View More RRF Modals -->
@foreach ($indicators as $indicator)
    <div class="modal modal-blur fade" id="viewMoreRrfModal-{{ $indicator->id }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $SelectedEntity->Entity }} Indicator Full Details (ID:
                        {{ $indicator->id }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Primary Category</th>
                                        <td>{{ $indicator->PrimaryCategory }}</td>
                                    </tr>
                                    <tr>
                                        <th>Secondary Category</th>
                                        <td>{{ $indicator->SecondaryCategory }}</td>
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
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Yearly Targets</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2024 (Year 1)</td>
                                        <td>{{ $indicator->TargetYearOne2024 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2025 (Year 2)</td>
                                        <td>{{ $indicator->TargetYearTwo2025 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2026 (Year 3)</td>
                                        <td>{{ $indicator->TargetYearThree2026 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2027 (Year 4)</td>
                                        <td>{{ $indicator->TargetYearFour2027 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2028 (Year 5)</td>
                                        <td>{{ $indicator->TargetYearFive2028 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2029 (Year 6)</td>
                                        <td>{{ $indicator->TargetYearSix2029 }}</td>
                                    </tr>
                                    <tr>
                                        <td>2030 (Year 7)</td>
                                        <td>{{ $indicator->TargetYearSeven2030 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>Timestamps</h4>
                            <table class="table table-bordered">
                                <tbody>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach


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

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('indicatorSearch');
        const tableRows = document.querySelectorAll('#indicatorsTable tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
</script>
