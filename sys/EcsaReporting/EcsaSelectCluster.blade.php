<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Select Cluster for Reporting
                </h2>
                <p class="text-muted mt-1">{{ $Desc }}</p>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('Ecsa_SelectTimeline') }}" class="btn btn-ghost-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                    Back to User Selection
                </a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Select a Cluster for {{ $userName }}</h3>
                        <form action="{{ route('Ecsa_SelectTimeline') }}" method="POST">
                            @csrf
                            <input type="hidden" name="UserID" value="{{ $user->UserID }}">
                            <input type="hidden" name="userName" value="{{ $userName }}">
                            <div class="form-floating mb-3">
                                <select class="form-select @error('ClusterID') is-invalid @enderror" id="ClusterID"
                                    name="ClusterID" required data-bs-toggle="select"
                                    data-placeholder="Choose a cluster..." data-allow-clear="true">
                                    <option value="">Select a cluster...</option>
                                    @foreach ($clusters as $cluster)
                                        <option value="{{ $cluster->ClusterID }}"
                                            {{ old('ClusterID') == $cluster->ClusterID ? 'selected' : '' }}>
                                            {{ $cluster->Cluster_Name }} - {{ $cluster->Description }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="ClusterID">Select Cluster</label>
                                @error('ClusterID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-chart-dots" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 3v18h18"></path>
                                        <path d="M9 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M19 7m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M14 15m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M10.16 10.62l2.34 2.88"></path>
                                        <path d="M15.088 13.328l2.837 -4.586"></path>
                                    </svg>
                                    Continue to Timeline Selection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-azure-lt">
                    <div class="card-body">
                        <h3 class="card-title text-azure">Selected User Details</h3>
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Name</div>
                                <div class="datagrid-content">{{ $userName }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Email</div>
                                <div class="datagrid-content">{{ $user->email }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Job Title</div>
                                <div class="datagrid-content">{{ $user->JobTitle ?? 'N/A' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Organization</div>
                                <div class="datagrid-content">{{ $user->ParentOrganization ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 bg-yellow-lt">
                    <div class="card-body">
                        <h3 class="card-title text-yellow">Why Select a Cluster?</h3>
                        <p class="text-muted">Selecting a cluster allows us to:</p>
                        <ul class="list-unstyled space-y-1">
                            <li class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-check text-yellow" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span class="ms-2">Focus on specific areas of responsibility</span>
                            </li>
                            <li class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-check text-yellow" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span class="ms-2">Streamline the reporting process</span>
                            </li>
                            <li class="d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-check text-yellow" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                <span class="ms-2">Ensure accurate data categorization</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('ClusterID'), {
            copyClassesToDropdown: false,
            dropdownParent: 'body',
            controlInput: '<input>',
            render: {
                item: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data
                            .customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function(data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data
                            .customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
        }));
    });
</script>
