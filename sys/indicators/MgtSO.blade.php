<!-- Page Header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <!-- Button to Open Add Strategic Objective Modal -->
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addStrategicObjectiveModal">
                        <i class="fas fa-plus"></i> <!-- Font Awesome plus icon -->
                        Add New Strategic Objective
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Strategic Objectives Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table tableme">
                    <thead>
                        <tr>
                            <th>SO Number</th>
                            <th>SO Name</th>
                            <th>Description</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($strategicObjectives as $objective)
                            <tr>
                                <td>{{ $objective->SO_Number }}</td>
                                <td>{{ $objective->SO_Name }}</td>
                                <td>{{ $objective->Description }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editStrategicObjectiveModal-{{ $objective->id }}">
                                            <i class="fas fa-edit"></i> <!-- Font Awesome edit icon -->
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $objective->id }}"
                                            action="{{ route('MassDelete', $objective->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $objective->id }}')">
                                                <i class="fas fa-trash"></i> <!-- Font Awesome trash icon -->
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- Optionally handle the empty state here, e.g., show a message -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add New Strategic Objective Modal -->
<div class="modal fade" id="addStrategicObjectiveModal" tabindex="-1" aria-labelledby="addStrategicObjectiveModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addStrategicObjectiveModalLabel">Add New Strategic Objective</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('MassInsert') }}" method="POST" id="addStrategicObjectiveForm">
                    @csrf
                    <div class="mb-3">
                        <label for="SO_Number" class="form-label">SO Number</label>
                        <input type="text" class="form-control" id="SO_Number" name="SO_Number" required>
                    </div>

                    <div class="mb-3">
                        <label for="SO_Name" class="form-label">SO Name</label>
                        <input type="text" class="form-control" id="SO_Name" name="SO_Name" required>
                    </div>

                    <input type="hidden" name="TableName" value="strategic_objectives">
                    <!-- Keep or adapt as needed based on your existing architecture -->
                    <input type="hidden" name="StrategicObjectiveID"
                        value="{{ md5(uniqid() . date('now') . uniqid()) }}">

                    <div class="mb-3">
                        <label for="Description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="Description" name="Description" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addStrategicObjectiveForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Strategic Objective Modals -->
@foreach ($strategicObjectives as $objective)
    <div class="modal fade" id="editStrategicObjectiveModal-{{ $objective->id }}" tabindex="-1"
        aria-labelledby="editStrategicObjectiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editStrategicObjectiveModalLabel">Edit Strategic Objective</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('MassUpdate', $objective->id) }}" method="POST"
                        id="editStrategicObjectiveForm-{{ $objective->id }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="TableName" value="strategic_objectives">
                        <input type="hidden" name="id" value="{{ $objective->id }}">

                        <div class="mb-3">
                            <label for="SO_Number" class="form-label">SO Number</label>
                            <input type="text" class="form-control" id="SO_Number" name="SO_Number"
                                value="{{ $objective->SO_Number }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="SO_Name" class="form-label">SO Name</label>
                            <input type="text" class="form-control" id="SO_Name" name="SO_Name"
                                value="{{ $objective->SO_Name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="Description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="Description" name="Description"
                                value="{{ $objective->Description }}" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editStrategicObjectiveForm-{{ $objective->id }}"
                        class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- SweetAlert2 Script for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(objectiveId) {
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
                document.getElementById('delete-form-' + objectiveId).submit();
            }
        });
    }
</script>
