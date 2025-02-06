<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <span class="avatar avatar-lg rounded bg-primary-subtle">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </span>
                </div>
                <div class="col">
                    <h2 class="page-title mb-2">
                        Available Reports
                    </h2>
                    <div class="text-muted">
                        {{ $Desc }}
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('Ecsa_SelectCluster') }}"
                            class="btn btn-outline-primary d-none d-sm-inline-block">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Cluster Selection
                        </a>
                        <a href="{{ route('Ecsa_SelectCluster') }}" class="btn btn-outline-primary d-sm-none btn-icon"
                            aria-label="Back to Cluster Selection">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card card-lg">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Available Reports</h3>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Report Name</th>
                                            <th>Type</th>
                                            <th>Closing Date</th>
                                            <th>Status</th>
                                            <th class="w-1"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($timelines as $timeline)
                                            <tr>
                                                <td>{{ $timeline->ReportName }}</td>
                                                <td>{{ $timeline->Type }}</td>
                                                <td>{{ \Carbon\Carbon::parse($timeline->ClosingDate)->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $timeline->status === 'Completed' ? 'success' : ($timeline->status === 'In Progress' ? 'warning' : 'secondary') }}">
                                                        {{ $timeline->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <form action="{{ route('Ecsa_SelectStrategicObjective') }}"
                                                        method="POST">
                                                        @csrf
                                                        <!-- Pass the necessary values to the next step -->
                                                        <input type="hidden" name="UserID"
                                                            value="{{ $UserID }}">
                                                        <input type="hidden" name="ClusterID"
                                                            value="{{ $ClusterID }}">
                                                        <input type="hidden" name="ReportingID"
                                                            value="{{ $timeline->ReportingID }}">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            Select
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 767.98px) {
        .table-responsive .table {
            min-width: 45rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseover', function() {
                this.classList.add('animate__animated', 'animate__pulse');
            });
            row.addEventListener('mouseout', function() {
                this.classList.remove('animate__animated', 'animate__pulse');
            });
        });
    });
</script>
