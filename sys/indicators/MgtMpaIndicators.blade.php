<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Manage Indicators for {{ $SelectedEntity->Entity }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#addIndicatorModal">
                            <i class="fas fa-plus me-2"></i>
                            Add New Indicator
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#addIndicatorModal" aria-label="Add New Indicator">
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Indicators List</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select class="form-select form-select-sm" aria-label="Items per page">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" class="form-control form-control-sm"
                                    aria-label="Search indicators" id="indicatorSearch">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Indicator</th>
                                <th>Reporting Period</th>
                                <th>Baseline 2024</th>
                                <th>Target 2025</th>
                                <th>Target 2026</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($indicators as $indicator)
                                <tr>
                                    <td>{{ $indicator->Indicator }}</td>
                                    <td>{{ $indicator->ReportingPeriod }}</td>
                                    <td>{{ $indicator->Baseline2024 ?? '-' }}</td>
                                    <td>{{ $indicator->TargetYearTwo2025 ?? '-' }}</td>
                                    <td>{{ $indicator->TargetYearThree2026 ?? '-' }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="#" class="btn btn-white btn-icon" data-bs-toggle="modal"
                                                data-bs-target="#editIndicatorModal-{{ $indicator->id }}"
                                                aria-label="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-white btn-icon" data-bs-toggle="modal"
                                                data-bs-target="#viewMoreModal-{{ $indicator->id }}" aria-label="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form id="delete-form-{{ $indicator->id }}"
                                                action="{{ route('mpaIndicators.DeleteIndicator') }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $indicator->id }}">
                                                <button type="button" class="btn btn-white btn-icon"
                                                    onclick="confirmDelete('{{ $indicator->id }}')"
                                                    aria-label="Delete">
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
                                                Try adjusting your search or filter to find what you're looking for.
                                            </p>
                                            <div class="empty-action">
                                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#addIndicatorModal">
                                                    <i class="fas fa-plus me-2"></i>
                                                    Add New Indicator
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i>
                                prev
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                next
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Indicator Modal -->
<div class="modal modal-blur fade" id="addIndicatorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Indicator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mpaIndicators.StoreIndicator') }}" method="POST" id="addIndicatorForm">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Primary Category</label>
                            <select class="form-select" name="IndicatorPrimaryCategory" required>
                                <option value="CRF" selected>CRF</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Secondary Category</label>
                            <select class="form-select" name="IndicatorSecondaryCategory" required>
                                <option value="" disabled selected>Select Secondary Category</option>
                                <option value="CRF PDO">CRF PDO</option>
                                <option value="CRF Intermediate">CRF Intermediate</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label">Indicator</label>
                            <textarea type="text" class="form-control" name="Indicator" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Definition</label>
                            <textarea class="form-control" name="IndicatorDefinition" rows="3"></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Question</label>
                            <textarea class="form-control" name="IndicatorQuestion" rows="3"></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Source of Data</label>
                            <input type="text" class="form-control" name="SourceOfData">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Response Type</label>
                            <select class="form-select" name="ResponseType" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Text">Text</option>
                                <option value="Number">Number</option>
                                <option value="Boolean">Boolean</option>
                                <option value="Yes/No">Yes/No</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Reporting Period</label>
                            <select class="form-select" name="ReportingPeriod" required>
                                <option value="" disabled selected>Select Period</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Bi-Annual">Bi-Annual</option>
                                <option value="Annually Reported">Annually Reported</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Expected Target</label>
                            <input type="text" class="form-control" name="ExpectedTarget">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Baseline PAD 2023</label>
                            <input type="text" class="form-control" name="BaselinePAD2023">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Baseline 2024</label>
                            <input type="text" class="form-control" name="Baseline2024">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Target 2024 (Year 1)</label>
                            <input type="text" class="form-control" name="TargetYearOne2024">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Target 2025 (Year 2)</label>
                            <input type="text" class="form-control" name="TargetYearTwo2025">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Target 2026 (Year 3)</label>
                            <input type="text" class="form-control" name="TargetYearThree2026">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Target 2027 (Year 4)</label>
                            <input type="text" class="form-control" name="TargetYearFour2027">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Target 2028 (Year 5)</label>
                            <input type="text" class="form-control" name="TargetYearFive2028">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Target 2029 (Year 6)</label>
                            <input type="text" class="form-control" name="TargetYearSix2029">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Target 2030 (Year 7)</label>
                            <input type="text" class="form-control" name="TargetYearSeven2030">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks / Comments</label>
                        <textarea class="form-control" name="RemarksComments" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="fas fa-plus me-2"></i>
                        Add Indicator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Indicator Modal -->
@foreach ($indicators as $indicator)
    <div class="modal modal-blur fade" id="editIndicatorModal-{{ $indicator->id }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Indicator (ID: {{ $indicator->id }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mpaIndicators.UpdateIndicator') }}" method="POST"
                    id="editIndicatorForm-{{ $indicator->id }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $indicator->id }}">
                    <input type="hidden" name="EntityID" value="{{ $SelectedEntity->EntityID }}">
                    <input type="hidden" name="IID" value="{{ $indicator->IID }}">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Primary Category</label>
                                <select class="form-select" name="IndicatorPrimaryCategory" required>
                                    <option value="RRF" @if ($indicator->PrimaryCategory === 'RRF') selected @endif>RRF
                                    </option>
                                    <option value="CRF" @if ($indicator->PrimaryCategory === 'CRF') selected @endif>CRF
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Secondary Category</label>
                                <select class="form-select" name="IndicatorSecondaryCategory" required>
                                    <option value="" disabled>Select Secondary Category</option>
                                    <option value="CRF PDO" @if ($indicator->SecondaryCategory === 'CRF PDO') selected @endif>
                                        CRF PDO
                                    </option>
                                    <option value="CRF Intermediate" @if ($indicator->SecondaryCategory === 'CRF Intermediate') selected @endif>
                                        CRF Intermediate
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Indicator</label>
                                <input type="text" class="form-control" name="Indicator"
                                    value="{{ $indicator->Indicator }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Definition</label>
                                <textarea class="form-control" name="IndicatorDefinition" rows="3">{{ $indicator->IndicatorDefinition }}</textarea>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Question</label>
                                <textarea class="form-control" name="IndicatorQuestion" rows="3">{{ $indicator->IndicatorQuestion }}</textarea>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Source Of Data</label>
                                <input type="text" class="form-control" name="SourceOfData"
                                    value="{{ $indicator->SourceOfData }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Response Type</label>
                                <select class="form-select" name="ResponseType" required>
                                    <option value="" disabled>Select Type</option>
                                    <option value="Text" @if ($indicator->ResponseType === 'Text') selected @endif>Text
                                    </option>
                                    <option value="Number" @if ($indicator->ResponseType === 'Number') selected @endif>Number
                                    </option>
                                    <option value="Boolean" @if ($indicator->ResponseType === 'Boolean') selected @endif>Boolean
                                    </option>
                                    <option value="Yes/No" @if ($indicator->ResponseType === 'Yes/No') selected @endif>Yes/No
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Reporting Period</label>
                                <select class="form-select" name="ReportingPeriod" required>
                                    <option value="Quarterly" @if ($indicator->ReportingPeriod === 'Quarterly') selected @endif>
                                        Quarterly</option>
                                    <option value="Bi-Annual" @if ($indicator->ReportingPeriod === 'Bi-Annual') selected @endif>
                                        Bi-Annual</option>
                                    <option value="Annual" @if ($indicator->ReportingPeriod === 'Annual') selected @endif>Annual
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Expected Target</label>
                                <input type="text" class="form-control" name="ExpectedTarget"
                                    value="{{ $indicator->ExpectedTarget }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Baseline PAD 2023</label>
                                <input type="text" class="form-control" name="BaselinePAD2023"
                                    value="{{ $indicator->BaselinePAD2023 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Baseline 2024</label>
                                <input type="text" class="form-control" name="Baseline2024"
                                    value="{{ $indicator->Baseline2024 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Target 2024 (Year 1)</label>
                                <input type="text" class="form-control" name="TargetYearOne2024"
                                    value="{{ $indicator->TargetYearOne2024 }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Target 2025 (Year 2)</label>
                                <input type="text" class="form-control" name="TargetYearTwo2025"
                                    value="{{ $indicator->TargetYearTwo2025 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Target 2026 (Year 3)</label>
                                <input type="text" class="form-control" name="TargetYearThree2026"
                                    value="{{ $indicator->TargetYearThree2026 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Target 2027 (Year 4)</label>
                                <input type="text" class="form-control" name="TargetYearFour2027"
                                    value="{{ $indicator->TargetYearFour2027 }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <label class="form-label">Target 2028 (Year 5)</label>
                                <input type="text" class="form-control" name="TargetYearFive2028"
                                    value="{{ $indicator->TargetYearFive2028 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Target 2029 (Year 6)</label>
                                <input type="text" class="form-control" name="TargetYearSix2029"
                                    value="{{ $indicator->TargetYearSix2029 }}">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Target 2030 (Year 7)</label>
                                <input type="text" class="form-control" name="TargetYearSeven2030"
                                    value="{{ $indicator->TargetYearSeven2030 }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks / Comments</label>
                            <textarea class="form-control" name="RemarksComments" rows="3">{{ $indicator->RemarksComments }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="fas fa-check me-2"></i>
                            Update Indicator
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- View More Modal -->
@foreach ($indicators as $indicator)
    <div class="modal modal-blur fade" id="viewMoreModal-{{ $indicator->id }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Indicator Full Details (ID: {{ $indicator->id }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
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
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
                                    <tbody>
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
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
                                    <tbody>
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
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
                                    <tbody>
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
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
                                    <tbody>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="table table-vcenter card-table">
                                    <tbody>
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
        const tableRows = document.querySelectorAll('.datatable tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
</script>
