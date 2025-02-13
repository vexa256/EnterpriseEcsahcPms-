<div class="page-body">
    <div class="container-xl">
        <!-- Timelines Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Last Bi-Annual</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($timelines as $timeline)
                            <tr>
                                {{-- <td>{{ $timeline->id }}</td> --}}
                                <td>{{ $timeline->ReportName }}</td>
                                <td>{{ $timeline->Type }}</td>
                                <td>{{ $timeline->Year }}</td>
                                <td>
                                    <span
                                        class="badge text-light bg-{{ $timeline->status == 'Completed' ? 'success' : ($timeline->status == 'In Progress' ? 'warning' : 'danger') }}">
                                        {{ $timeline->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($timeline->Type == 'Bi-Annual')
                                        {{ $timeline->LastBiAnnual ? 'Yes' : 'No' }}
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


                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- <tr>
                                <td colspan="7" class="text-center">No timelines found.</td>
                            </tr> --}}
                        @endforelse
                    </tbody>
                </table>
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
                    <h5 class="modal-title" id="editTimelineModalLabel">Edit MPA Reporting Timeline Status :
                        {{ $timeline->ReportName }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('MassUpdate', $timeline->id) }}" method="POST"
                        id="editTimelineForm-{{ $timeline->id }}">
                        @csrf
                        @method('PUT')
                        {{-- <div class="mb-3"> --}}
                        <input type="hidden" name="TableName" value="mpa_timelines">

                        <input type="hidden" name="id" value="{{ $timeline->id }}">

                        {{-- </div> --}}



                        <div class="mb-3">
                            <label for="status-{{ $timeline->id }}" class="form-label">Status</label>
                            <select class="form-control" id="status-{{ $timeline->id }}" name="status" required>
                                <option value="Pending" {{ $timeline->status == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="In Progress" {{ $timeline->status == 'In Progress' ? 'selected' : '' }}>
                                    In Progress</option>
                                <option value="Completed" {{ $timeline->status == 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
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
                if (selectElem.value === 'Bi-Annual') {
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
