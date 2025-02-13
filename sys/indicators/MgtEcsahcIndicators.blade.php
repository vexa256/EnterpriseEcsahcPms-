@php
    $isAdmin = auth()->check() && auth()->user()->AccountRole === 'Admin';
@endphp

<!-- Page Header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if ($isAdmin)
                        <!-- Button to open "Add New Indicator" modal -->
                        <button type="button" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
                            data-bs-target="#addIndicatorModal">
                            <i class="fas fa-plus"></i> <!-- Font Awesome plus icon -->
                            Add New Indicator
                        </button>
                    @endif
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
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Indicator</th>
                            <th>Baseline (2023/2024)</th>
                            <th>Year 1</th>
                            <th>Year 2</th>
                            <th>Year 3</th>
                            <th>Response Type</th>
                            <th>Cluster(s)</th>
                            @if ($isAdmin)
                                <th class="w-1">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indicators as $indicator)
                            <tr>
                                <td>{{ $indicator->Indicator_Number }}</td>
                                <td>{{ $indicator->Indicator_Name }}</td>
                                <td>{{ $indicator->Baseline_2023_2024 }}</td>
                                <td>{{ $indicator->Target_Year1 }}</td>
                                <td>{{ $indicator->Target_Year2 }}</td>
                                <td>{{ $indicator->Target_Year3 }}</td>
                                <td>{{ $indicator->ResponseType }}</td>
                                <td>{{ $indicator->Responsible_Cluster }}</td>
                                @if ($isAdmin)
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#editIndicatorModal-{{ $indicator->id }}">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>

                                            <!-- Delete Button -->
                                            <form id="delete-form-{{ $indicator->id }}"
                                                action="{{ route('DeleteEcsahcIndicators') }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $indicator->id }}">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ $indicator->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                    Trash
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <!-- Optional: Message if no indicators are found -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if ($isAdmin)
    <!-- Add New Indicator Modal -->
    <div class="modal fade" id="addIndicatorModal" aria-labelledby="addIndicatorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addIndicatorModalLabel">
                        Add New Indicator ({{ $strategicObjectives->SO_Name }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('AddEcsahcIndicators') }}" method="POST" id="addIndicatorForm"
                        class="row">
                        @csrf

                        <!-- Hidden: Strategic Objective ID -->
                        <input type="hidden" name="StrategicObjectiveID" value="{{ $StrategicObjectiveID }}">
                        <input type="hidden" name="IndicatorID"
                            value="{{ md5(md5(uniqid() . date('now') . $StrategicObjectiveID)) }}">

                        <!-- Indicator Number -->
                        <div class="col-4 mb-3">
                            <label for="Indicator_Number" class="form-label">Indicator Number</label>
                            <input type="text" class="form-control" id="Indicator_Number" name="Indicator_Number"
                                required>
                        </div>

                        <!-- Indicator Name -->
                        <div class="col-4 mb-3">
                            <label for="Indicator_Name" class="form-label">Indicator Name</label>
                            <input type="text" class="form-control" id="Indicator_Name" name="Indicator_Name"
                                required>
                        </div>

                        <!-- Baseline (2023/2024) -->
                        <div class="col-4 mb-3">
                            <label for="Baseline_2023_2024" class="form-label">Baseline (2023/2024)</label>
                            <input type="number" class="form-control" id="Baseline_2023_2024"
                                name="Baseline_2023_2024">
                        </div>

                        <!-- Target Year 1 -->
                        <div class="col-4 mb-3">
                            <label for="Target_Year1" class="form-label">Target Year 1</label>
                            <input type="number" class="form-control" id="Target_Year1" name="Target_Year1">
                        </div>

                        <!-- Target Year 2 -->
                        <div class="col-4 mb-3">
                            <label for="Target_Year2" class="form-label">Target Year 2</label>
                            <input type="number" class="form-control" id="Target_Year2" name="Target_Year2">
                        </div>

                        <!-- Target Year 3 -->
                        <div class="col-4 mb-3">
                            <label for="Target_Year3" class="form-label">Target Year 3</label>
                            <input type="number" class="form-control" id="Target_Year3" name="Target_Year3">
                        </div>

                        <!-- Response Type -->
                        <div class="col-4 mb-3">
                            <label for="ResponseType" class="form-label">Response Type</label>
                            <select class="form-select" id="ResponseType" name="ResponseType" required>
                                <option value="Number">Number</option>
                                <option value="Text">Text</option>
                                <option value="Number">Number</option>
                                <option value="Boolean">Boolean</option>
                                <option value="Yes/No">Yes/No</option>
                            </select>
                        </div>

                        <!-- Responsible Cluster(s) (TomSelect) -->
                        <div class="col-4 mb-3">
                            <label class="form-label">Responsible Cluster(s)</label>
                            <select name="Responsible_Cluster[]" class="form-select" id="select-states" multiple>
                                @foreach ($clusters as $cluster)
                                    <option value="{{ $cluster->ClusterID }}">
                                        {{ $cluster->Cluster_Name }}
                                    </option>
                                @endforeach
                            </select>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    if (window.TomSelect) {
                                        const el = document.getElementById('select-states');
                                        new TomSelect(el, {
                                            copyClassesToDropdown: false,
                                            dropdownParent: 'body',
                                            controlInput: '<input>',
                                            render: {
                                                item: function(data, escape) {
                                                    if (data.customProperties) {
                                                        return '<div><span class="dropdown-item-indicator">' +
                                                            data.customProperties + '</span>' +
                                                            escape(data.text) + '</div>';
                                                    }
                                                    return '<div>' + escape(data.text) + '</div>';
                                                },
                                                option: function(data, escape) {
                                                    if (data.customProperties) {
                                                        return '<div><span class="dropdown-item-indicator">' +
                                                            data.customProperties + '</span>' +
                                                            escape(data.text) + '</div>';
                                                    }
                                                    return '<div>' + escape(data.text) + '</div>';
                                                },
                                            },
                                        });
                                    }
                                });
                            </script>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" form="addIndicatorForm" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Edit Indicator Modals -->
@foreach ($indicators as $indicator)
    @if ($isAdmin)
        <div class="modal fade" id="editIndicatorModal-{{ $indicator->id }}" tabindex="-1"
            aria-labelledby="editIndicatorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-xl">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editIndicatorModalLabel">
                            Edit Indicator
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('UpdateEcsahcIndicators') }}" method="POST"
                            id="editIndicatorForm-{{ $indicator->id }}" class="row">
                            @csrf
                            @method('PUT')

                            <!-- Primary Key (hidden) -->
                            <input type="hidden" name="id" value="{{ $indicator->id }}">

                            <!-- Strategic Objective ID -->
                            <input type="hidden" name="StrategicObjectiveID" value="{{ $StrategicObjectiveID }}">

                            <!-- Indicator Number -->
                            <div class="col-4 mb-3">
                                <label for="Indicator_Number" class="form-label">Indicator Number</label>
                                <input type="text" class="form-control" id="Indicator_Number"
                                    name="Indicator_Number" value="{{ $indicator->Indicator_Number }}" required>
                            </div>

                            <!-- Indicator Name -->
                            <div class="col-4 mb-3">
                                <label for="Indicator_Name" class="form-label">Indicator Name</label>
                                <input type="text" class="form-control" id="Indicator_Name" name="Indicator_Name"
                                    value="{{ $indicator->Indicator_Name }}" required>
                            </div>

                            <!-- Baseline (2023/2024) -->
                            <div class="col-4 mb-3">
                                <label for="Baseline_2023_2024" class="form-label">Baseline (2023/2024)</label>
                                <input type="number" class="form-control" id="Baseline_2023_2024"
                                    name="Baseline_2023_2024" value="{{ $indicator->Baseline_2023_2024 }}">
                            </div>

                            <!-- Target Year 1 -->
                            <div class="col-4 mb-3">
                                <label for="Target_Year1" class="form-label">Target Year 1</label>
                                <input type="number" class="form-control" id="Target_Year1" name="Target_Year1"
                                    value="{{ $indicator->Target_Year1 }}">
                            </div>

                            <!-- Target Year 2 -->
                            <div class="col-4 mb-3">
                                <label for="Target_Year2" class="form-label">Target Year 2</label>
                                <input type="number" class="form-control" id="Target_Year2" name="Target_Year2"
                                    value="{{ $indicator->Target_Year2 }}">
                            </div>

                            <!-- Target Year 3 -->
                            <div class="col-4 mb-3">
                                <label for="Target_Year3" class="form-label">Target Year 3</label>
                                <input type="number" class="form-control" id="Target_Year3" name="Target_Year3"
                                    value="{{ $indicator->Target_Year3 }}">
                            </div>

                            <!-- Response Type -->
                            <div class="col-4 mb-3">
                                <label for="ResponseType" class="form-label">Response Type</label>
                                <select class="form-select" id="ResponseType" name="ResponseType" required>
                                    <option value="Number" @if ($indicator->ResponseType == 'Number') selected @endif>Number
                                    </option>
                                    <option value="Text" @if ($indicator->ResponseType == 'Text') selected @endif>Text
                                    </option>
                                    <option value="Number" @if ($indicator->ResponseType == 'Number') selected @endif>Number
                                    </option>
                                    <option value="Boolean" @if ($indicator->ResponseType == 'Boolean') selected @endif>Boolean
                                    </option>
                                    <option value="Yes/No" @if ($indicator->ResponseType == 'Yes/No') selected @endif>Yes/No
                                    </option>
                                </select>
                            </div>

                            <!-- Responsible Cluster(s) -->
                            @php
                                // Decode the existing JSON so we know which clusters to mark as selected
                                $existingClusters = json_decode($indicator->Responsible_Cluster, true) ?? [];
                            @endphp
                            <div class="col-4 mb-3">
                                <label class="form-label">Responsible Cluster(s)</label>
                                <select name="Responsible_Cluster[]" class="form-select"
                                    id="select-states-{{ $indicator->id }}" multiple>
                                    @foreach ($clusters as $cluster)
                                        <option value="{{ $cluster->ClusterID }}"
                                            @if (in_array($cluster->ClusterID, $existingClusters)) selected @endif>
                                            {{ $cluster->Cluster_Name }}
                                        </option>
                                    @endforeach
                                </select>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        if (window.TomSelect) {
                                            const selectEl = document.getElementById('select-states-{{ $indicator->id }}');
                                            new TomSelect(selectEl, {
                                                copyClassesToDropdown: false,
                                                dropdownParent: 'body',
                                                controlInput: '<input>',
                                                render: {
                                                    item: function(data, escape) {
                                                        if (data.customProperties) {
                                                            return '<div><span class="dropdown-item-indicator">' +
                                                                data.customProperties + '</span>' +
                                                                escape(data.text) + '</div>';
                                                        }
                                                        return '<div>' + escape(data.text) + '</div>';
                                                    },
                                                    option: function(data, escape) {
                                                        if (data.customProperties) {
                                                            return '<div><span class="dropdown-item-indicator">' +
                                                                data.customProperties + '</span>' +
                                                                escape(data.text) + '</div>';
                                                        }
                                                        return '<div>' + escape(data.text) + '</div>';
                                                    },
                                                },
                                            });
                                        }
                                    });
                                </script>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" form="editIndicatorForm-{{ $indicator->id }}"
                            class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- SweetAlert2 Script for Delete Confirmation -->
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
