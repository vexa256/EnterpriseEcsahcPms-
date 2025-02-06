<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <span class="avatar avatar-lg rounded bg-primary-subtle">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </span>
                </div>
                <div class="col">
                    <h2 class="page-title mb-2">
                        Performance Overview
                    </h2>
                    <div class="text-muted">
                        {{ $Desc }}
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('select-report', ['year' => $year]) }}"
                            class="btn btn-outline-primary d-none d-sm-inline-block">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Report Selection
                        </a>
                        <a href="{{ route('select-report', ['year' => $year]) }}"
                            class="btn btn-outline-primary d-sm-none btn-icon" aria-label="Back to Report Selection">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <a href="{{ route('export-csv', ['reportingId' => $reportingId]) }}" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Strategic Objectives Overview</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-around mb-4">
                                @php
                                    $totalObjectives = count($report);
                                    $metCount = $progressingCount = $notPerformingCount = $naCount = 0;
                                    foreach ($report as $data) {
                                        switch ($data['overall_status'] ?? 'N/A') {
                                            case 'Targets Met':
                                                $metCount++;
                                                break;
                                            case 'Progressing':
                                                $progressingCount++;
                                                break;
                                            case 'Not Performing':
                                                $notPerformingCount++;
                                                break;
                                            default:
                                                $naCount++;
                                        }
                                    }
                                @endphp
                                <div class="text-center px-3">
                                    <div class="fs-2 fw-bold text-success">{{ $metCount }}</div>
                                    <div class="text-muted">Targets Met</div>
                                </div>
                                <div class="text-center px-3">
                                    <div class="fs-2 fw-bold text-warning">{{ $progressingCount }}</div>
                                    <div class="text-muted">Progressing</div>
                                </div>
                                <div class="text-center px-3">
                                    <div class="fs-2 fw-bold text-danger">{{ $notPerformingCount }}</div>
                                    <div class="text-muted">Not Performing</div>
                                </div>
                                <div class="text-center px-3">
                                    <div class="fs-2 fw-bold text-secondary">{{ $naCount }}</div>
                                    <div class="text-muted">N/A</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($report as $soId => $data)
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $data['strategic_objective']->SO_Name }}</h3>
                                <div class="card-actions">
                                    <span
                                        class="badge bg-{{ $data['overall_status'] === 'Targets Met' ? 'success' : ($data['overall_status'] === 'Progressing' ? 'warning' : ($data['overall_status'] === 'Not Performing' ? 'danger' : 'secondary')) }}">
                                        {{ $data['overall_status'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">{{ $data['strategic_objective']->Description }}</p>
                                <div class="mt-3">
                                    <h4 class="card-title">Performance Indicators</h4>
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <thead>
                                                <tr>
                                                    <th>Indicator</th>
                                                    <th>Score</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                    @if (is_array($item) && isset($item['indicator']))
                                                        <tr>
                                                            <td>{{ $item['indicator']->Indicator_Name }}</td>
                                                            <td>{{ is_array($item['score']) ? implode('/', $item['score']) : $item['score'] }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $item['status'] === 'met' ? 'success' : ($item['status'] === 'progressing' ? 'warning' : ($item['status'] === 'not performing' ? 'danger' : 'secondary')) }}">
                                                                    {{ ucfirst($item['status']) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
            min-width: 30rem;
        }
    }

    .badge {
        font-size: 0.875em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseover', function() {
                this.classList.add('animate__animated', 'animate__pulse');
            });
            card.addEventListener('mouseout', function() {
                this.classList.remove('animate__animated', 'animate__pulse');
            });
        });
    });
</script>
