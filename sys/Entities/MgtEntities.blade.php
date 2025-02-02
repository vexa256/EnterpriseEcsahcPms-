<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">

            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <!-- Button to Open Add Entity Modal -->
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addEntityModal">
                        <i class="fas fa-plus"></i> <!-- Font Awesome plus icon -->
                        Add New Entity
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Entities Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table tableme">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entity Name</th>
                            <th>Details</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($entities as $entity)
                            <tr>
                                <td>{{ $entity->id }}</td>
                                <td>{{ $entity->Entity }}</td>
                                <td>{{ Str::limit($entity->EntityProjectDetails, 50) }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editEntityModal-{{ $entity->id }}">
                                            <i class="fas fa-edit"></i> <!-- Font Awesome edit icon -->
                                            Edit
                                        </button>

                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $entity->id }}" action="{{ route('MassDelete') }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $entity->id }}">
                                            <input type="hidden" name="TableName" value="mpa_entities">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $entity->id }}')">
                                                <i class="fas fa-trash"></i> <!-- Font Awesome trash icon -->
                                                Trash
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

<!-- Add New Entity Modal -->
<div class="modal fade" id="addEntityModal" tabindex="-1" aria-labelledby="addEntityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEntityModalLabel">Add New Entity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('MassInsert') }}" method="POST" id="addEntityForm">
                    @csrf
                    <input type="hidden" name="TableName" value="mpa_entities">
                    <div class="mb-3">
                        <label for="Entity" class="form-label">Entity Name</label>
                        <input type="text" class="form-control" id="Entity" name="Entity" required>
                    </div>
                    <div class="mb-3">
                        <label for="EntityID" class="form-label">Entity ID</label>
                        <input readonly value="{{ md5(uniqid() . strtotime('now')) }}" type="text"
                            class="form-control" id="EntityID" name="EntityID" required>
                    </div>
                    <div class="mb-3">
                        <label for="EntityProjectDetails" class="form-label">Details</label>
                        <textarea class="form-control" id="EntityProjectDetails" name="EntityProjectDetails" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addEntityForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Entity Modals -->
@foreach ($entities as $entity)
    <div class="modal fade" id="editEntityModal-{{ $entity->id }}" tabindex="-1"
        aria-labelledby="editEntityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editEntityModalLabel">Edit Entity</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('MassUpdate') }}" method="POST"
                        id="editEntityForm-{{ $entity->id }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $entity->id }}">
                        <input type="hidden" name="TableName" value="mpa_entities">
                        <div class="mb-3">
                            <label for="Entity" class="form-label">Entity Name</label>
                            <input type="text" class="form-control" id="Entity" name="Entity"
                                value="{{ $entity->Entity }}" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="EntityID" class="form-label">Entity ID</label>
                            <input type="text" class="form-control" id="EntityID" name="EntityID"
                                value="{{ $entity->EntityID }}" required>
                        </div> --}}
                        <div class="mb-3">
                            <label for="EntityProjectDetails" class="form-label">Details</label>
                            <textarea class="form-control" id="EntityProjectDetails" name="EntityProjectDetails" rows="3">{{ $entity->EntityProjectDetails }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editEntityForm-{{ $entity->id }}"
                        class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- SweetAlert2 Script for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(entityId) {
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
                document.getElementById('delete-form-' + entityId).submit();
            }
        });
    }
</script>
