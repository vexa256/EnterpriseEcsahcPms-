<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <!-- Button to Open Add Timeline Modal -->
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addTimelineModal">
                        <i class="fas fa-plus"></i> <!-- Font Awesome plus icon -->
                        Add New Timeline
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Timelines Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Year</th>
                            <th>Closing Date</th>
                            <th>Status</th>
                            <th>Last Bi-Annual</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($timelines as $timeline)
                            <tr>
                                <td>{{ $timeline->id }}</td>
                                <td>{{ $timeline->ReportName }}</td>
                                <td>{{ $timeline->Type }}</td>
                                <td>{{ $timeline->Year }}</td>
                                <td>{{ \Carbon\Carbon::parse($timeline->ClosingDate)->format('Y-m-d') }}</td>
                                <td>
                                    <span
                                        class="badge text-light bg-{{ $timeline->status == 'Completed' ? 'success' : ($timeline->status == 'In Progress' ? 'warning' : 'danger') }}">
                                        {{ $timeline->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($timeline->Type == 'Bi-Annual Reports')
                                        {{ $timeline->LastBiAnnual }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editTimelineModal-{{ $timeline->id }}">
                                            <i class="fas fa-edit"></i> <!-- Font Awesome edit icon -->
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $timeline->id }}"
                                            action="{{ route('MassDelete', $timeline->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="TableName" value="ecsahc_timelines">
                                            <input type="hidden" name="id" value="{{ $timeline->id }}">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $timeline->id }}')">
                                                <i class="fas fa-trash"></i> <!-- Font Awesome trash icon -->
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- <tr>
                                <td colspan="8" class="text-center">No timelines found.</td>
                            </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add New Timeline Modal -->
<div class="modal fade" id="addTimelineModal" tabindex="-1" aria-labelledby="addTimelineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTimelineModalLabel">Add New Timeline</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('MassInsert') }}" method="POST" id="addTimelineForm">
                    @csrf
                    <div class="mb-3">
                        <input type="hidden" name="TableName" value="ecsahc_timelines">

                        <label for="ReportName" class="form-label">Report Name</label>
                        <input type="text" class="form-control" id="ReportName" name="ReportName" required>
                    </div>
                    <div class="mb-3 d-none">
                        <label for="ReportingID" class="form-label">Reporting ID</label>
                        <input type="text" class="form-control" id="ReportingID" name="ReportingID"
                            placeholder="Auto-generated" readonly value="{{ md5(uniqid() . date('now')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="Type" class="form-label">Type</label>
                        <select class="form-control type-select" id="Type" name="Type" required>
                            <option value="Quarterly Reports">Quarterly Reports</option>
                            <option value="Bi-Annual Reports">Bi-Annual Reports</option>
                            <option value="Annual Reports">Annual Reports</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Description" class="form-label">Description</label>
                        <textarea class="form-control" id="Description" name="Description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Year" class="form-label">Year</label>
                        <input type="text" class="form-control" id="Year" name="Year" maxlength="4"
                            pattern="\d{4}" required>
                    </div>
                    <div class="mb-3">
                        <label for="ClosingDate" class="form-label">Closing Date</label>
                        <input type="date" class="form-control" id="ClosingDate" name="ClosingDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3 form-check last-biannual-wrapper">
                        <!-- Hidden input ensures a value is submitted when checkbox is unchecked -->
                        <input type="hidden" name="LastBiAnnual" value="No">
                        <input type="checkbox" class="form-check-input" id="LastBiAnnual" name="LastBiAnnual"
                            value="Yes">
                        <label class="form-check-label" for="LastBiAnnual">Last Bi-Annual</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addTimelineForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Timeline Modals -->
@foreach ($timelines as $timeline)
    <div class="modal fade" id="editTimelineModal-{{ $timeline->id }}" tabindex="-1"
        aria-labelledby="editTimelineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editTimelineModalLabel">Edit Timeline</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('MassUpdate', $timeline->id) }}" method="POST"
                        id="editTimelineForm-{{ $timeline->id }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <input type="hidden" name="TableName" value="ecsahc_timelines">
                            <input type="hidden" name="id" value="{{ $timeline->id }}">
                            <label for="ReportName-{{ $timeline->id }}" class="form-label">Report Name</label>
                            <input type="text" class="form-control" id="ReportName-{{ $timeline->id }}"
                                name="ReportName" value="{{ $timeline->ReportName }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="editType-{{ $timeline->id }}" class="form-label">Type</label>
                            <select class="form-control type-select" id="editType-{{ $timeline->id }}"
                                name="Type" required>
                                <option value="Quarterly Reports"
                                    {{ $timeline->Type == 'Quarterly Reports' ? 'selected' : '' }}>
                                    Quarterly Reports</option>
                                <option value="Bi-Annual Reports"
                                    {{ $timeline->Type == 'Bi-Annual Reports' ? 'selected' : '' }}>
                                    Bi-Annual Reports</option>
                                <option value="Annual Reports"
                                    {{ $timeline->Type == 'Annual Reports' ? 'selected' : '' }}>
                                    Annual Reports</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Description-{{ $timeline->id }}" class="form-label">Description</label>
                            <textarea class="form-control" id="Description-{{ $timeline->id }}" name="Description" rows="3">{{ $timeline->Description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="Year-{{ $timeline->id }}" class="form-label">Year</label>
                            <input type="text" class="form-control" id="Year-{{ $timeline->id }}" name="Year"
                                value="{{ $timeline->Year }}" maxlength="4" pattern="\d{4}" required>
                        </div>
                        <div class="mb-3">
                            <label for="ClosingDate-{{ $timeline->id }}" class="form-label">Closing Date</label>
                            <input type="date" class="form-control" id="ClosingDate-{{ $timeline->id }}"
                                name="ClosingDate" value="{{ $timeline->ClosingDate }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="status-{{ $timeline->id }}" class="form-label">Status</label>
                            <select class="form-control" id="status-{{ $timeline->id }}" name="status" required>
                                <option value="Pending" {{ $timeline->status == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="In Progress"
                                    {{ $timeline->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $timeline->status == 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check last-biannual-wrapper">
                            <!-- Hidden input ensures a value is submitted when checkbox is unchecked -->
                            <input type="hidden" name="LastBiAnnual" value="No">
                            <input type="checkbox" class="form-check-input"
                                id="editLastBiAnnual-{{ $timeline->id }}" name="LastBiAnnual" value="Yes"
                                {{ $timeline->LastBiAnnual == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="editLastBiAnnual-{{ $timeline->id }}">Last
                                Bi-Annual</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editTimelineForm-{{ $timeline->id }}"
                        class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- SweetAlert2 Script for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(timelineId) {
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
                document.getElementById('delete-form-' + timelineId).submit();
            }
        });
    }
</script>

<!-- Script to toggle the display of Last Bi-Annual based on Type selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.type-select').forEach(function(selectElem) {
            // Find the nearest form and the associated last-biannual wrapper inside it.
            var form = selectElem.closest('form');
            if (!form) return;
            var wrapper = form.querySelector('.last-biannual-wrapper');
            if (!wrapper) return;

            function toggleWrapper() {
                if (selectElem.value === 'Bi-Annual Reports') {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                    // Optionally, uncheck the checkbox when hiding
                    var checkbox = wrapper.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = false;
                }
            }

            // Initialize on page load
            toggleWrapper();

            // Attach change event
            selectElem.addEventListener('change', toggleWrapper);
        });
    });
</script>
