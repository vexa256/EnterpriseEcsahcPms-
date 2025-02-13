{{-- Page Header --}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <!-- Button to Open Add User Modal -->
                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> <!-- Font Awesome plus icon -->
                        Add New User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Table: Displaying Only Main Columns --}}
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table tableme">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Entity</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="w-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->Entity }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->Phone }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <!-- View More Button -->
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#viewUserModal-{{ $user->id }}">
                                            <i class="fas fa-eye"></i> View More
                                        </button>
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal-{{ $user->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('MassDelete') }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <input type="hidden" name="TableName" value="users">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $user->id }}')">
                                                <i class="fas fa-trash"></i> Trash
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add New User Modal (Full Form) --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('MassInsert') }}" method="POST" id="addUserForm">
                    @csrf
                    <input type="hidden" name="TableName" value="users">
                    <div class="row">
                        <!-- Row 1 -->
                        <div class="col-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <!-- Row 2 -->
                        <div class="col-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-6">
                            <label for="EntityID" class="form-label">Entity</label>
                            <select class="form-control" id="EntityID" name="EntityID">
                                <option value="">Select Entity</option>
                                @foreach ($entities as $entity)
                                    <option value="{{ $entity->EntityID }}">{{ $entity->EntityID }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <!-- Row 3 -->
                        <div class="col-6">
                            <input type="hidden" name="UserType" value="MPA">
                        </div>
                        <div class="col-4 d-none">
                            <label for="UserCode" class="form-label">User Code</label>
                            <input value="{{ md5(uniqid() . date('now')) }}" type="text" class="form-control"
                                id="UserCode" name="UserCode">
                        </div>
                        <div class="col-6">
                            <label for="Phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="Phone" name="Phone">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Row 4 -->
                        <div class="col-4">
                            <label for="Nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="Nationality" name="Nationality">
                        </div>
                        <div class="col-4">
                            <label for="PhoneNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="PhoneNumber" name="PhoneNumber">
                        </div>
                        <div class="col-4">
                            <label for="ParentOrganization" class="form-label">Parent Organization</label>
                            <input type="text" class="form-control" id="ParentOrganization"
                                name="ParentOrganization">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Row 5 -->
                        <div class="col-4">
                            <label for="Sex" class="form-label">Sex</label>
                            <select class="form-control" id="Sex" name="Sex">
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="JobTitle" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="JobTitle" name="JobTitle">
                        </div>
                        <div class="col-4">
                            <label for="AccountRole" class="form-label">Account Role</label>
                            <select class="form-control" id="AccountRole" name="AccountRole">
                                <option value="Admin">Admin</option>
                                <option value="User" selected>User</option>
                                <option value="Cluster Head">Cluster Head</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Row 6 -->
                        <div class="col-4 d-none">
                            <label for="UserID" class="form-label">User ID</label>
                            <input value="{{ md5(uniqid() . date('now')) }}" type="text" class="form-control"
                                id="UserID" name="UserID">
                        </div>
                        <div class="col-12">
                            <label for="Address" class="form-label">Address</label>
                            <textarea class="form-control" id="Address" name="Address" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addUserForm" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit User Modals --}}
@foreach ($users as $user)
    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1"
        aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('MassUpdate') }}" method="POST" id="editUserForm-{{ $user->id }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <input type="hidden" name="TableName" value="users">
                        <div class="row">
                            <!-- Row 1 -->
                            <div class="col-4">
                                <label for="name-{{ $user->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name-{{ $user->id }}"
                                    name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-4">
                                <label for="email-{{ $user->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email-{{ $user->id }}"
                                    name="email" value="{{ $user->email }}" required>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <!-- Row 2 -->
                            <div class="col-4">
                                <label for="password-{{ $user->id }}" class="form-label">
                                    Password <small>(leave blank to keep current)</small>
                                </label>
                                <input type="password" class="form-control" id="password-{{ $user->id }}"
                                    name="password">
                            </div>
                            <div class="col-4">
                                <label for="EntityID-{{ $user->id }}" class="form-label">Entity</label>
                                <select class="form-control" id="EntityID-{{ $user->id }}" name="EntityID">
                                    <option value="">Select Entity</option>
                                    @foreach ($entities as $entity)
                                        <option value="{{ $entity->EntityID }}"
                                            {{ $user->EntityID == $entity->EntityID ? 'selected' : '' }}>
                                            {{ $entity->Entity }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <!-- Row 3 -->

                            <div class="col-4">
                                <label for="UserCode-{{ $user->id }}" class="form-label">User Code</label>
                                <input type="text" class="form-control" id="UserCode-{{ $user->id }}"
                                    name="UserCode" value="{{ $user->UserCode }}">
                            </div>
                            <div class="col-4">
                                <label for="Phone-{{ $user->id }}" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="Phone-{{ $user->id }}"
                                    name="Phone" value="{{ $user->Phone }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Row 4 -->
                            <div class="col-4">
                                <label for="Nationality-{{ $user->id }}" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="Nationality-{{ $user->id }}"
                                    name="Nationality" value="{{ $user->Nationality }}">
                            </div>
                            <div class="col-4">
                                <label for="PhoneNumber-{{ $user->id }}" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="PhoneNumber-{{ $user->id }}"
                                    name="PhoneNumber" value="{{ $user->PhoneNumber }}">
                            </div>
                            <div class="col-4">
                                <label for="ParentOrganization-{{ $user->id }}" class="form-label">Parent
                                    Organization</label>
                                <input type="text" class="form-control"
                                    id="ParentOrganization-{{ $user->id }}" name="ParentOrganization"
                                    value="{{ $user->ParentOrganization }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Row 5 -->
                            <div class="col-4">
                                <label for="Sex-{{ $user->id }}" class="form-label">Sex</label>
                                <select class="form-control" id="Sex-{{ $user->id }}" name="Sex">
                                    <option value="">Select Sex</option>
                                    <option value="Male" {{ $user->Sex == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $user->Sex == 'Female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="JobTitle-{{ $user->id }}" class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="JobTitle-{{ $user->id }}"
                                    name="JobTitle" value="{{ $user->JobTitle }}">
                            </div>
                            <div class="col-4">
                                <label for="AccountRole-{{ $user->id }}" class="form-label">Account Role</label>
                                <select class="form-control" id="AccountRole-{{ $user->id }}"
                                    name="AccountRole">
                                    <option value="Admin" {{ $user->AccountRole == 'Admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="User" {{ $user->AccountRole == 'User' ? 'selected' : '' }}>User
                                    </option>
                                    <option value="Viewer" {{ $user->AccountRole == 'Viewer' ? 'selected' : '' }}>
                                        Viewer</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Row 6 -->

                            <div class="col-8">
                                <label for="Address-{{ $user->id }}" class="form-label">Address</label>
                                <textarea class="form-control" id="Address-{{ $user->id }}" name="Address" rows="2">{{ $user->Address }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editUserForm-{{ $user->id }}"
                        class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- View More Modals (Display All User Details) --}}
@foreach ($users as $user)
    <div class="modal fade" id="viewUserModal-{{ $user->id }}" tabindex="-1"
        aria-labelledby="viewUserModalLabel-{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewUserModalLabel-{{ $user->id }}">User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered ">
                            <tbody>
                                <tr>

                                    <th>Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>

                                </tr>
                                <tr>
                                    <th>Entity</th>
                                    <td>{{ $user->Entity }}</td>

                                </tr>
                                <tr>
                                    <th>User Type</th>
                                    <td>{{ $user->UserType }}</td>

                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $user->Phone }}</td>
                                    <th>Nationality</th>
                                    <td>{{ $user->Nationality }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ $user->PhoneNumber }}</td>
                                    <th>Parent Organization</th>
                                    <td>{{ $user->ParentOrganization }}</td>
                                </tr>
                                <tr>
                                    <th>Sex</th>
                                    <td>{{ $user->Sex }}</td>
                                    <th>Job Title</th>
                                    <td>{{ $user->JobTitle }}</td>
                                </tr>
                                <tr>
                                    <th>Account Role</th>
                                    <td>{{ $user->AccountRole }}</td>
                                    <th>UserID</th>
                                    <td>{{ $user->UserID }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $user->created_at }}</td>
                                    <th>Updated At</th>
                                    <td>{{ $user->updated_at }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3">{{ $user->Address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- SweetAlert2 Script for Delete Confirmation --}}

<script>
    function confirmDelete(userId) {
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
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }
</script>
