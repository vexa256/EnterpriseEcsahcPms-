<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="display-4 text-center mb-3">Select a Year</h1>
            <p class="text-muted text-center mb-5">
                Cluster:
                <span class="badge bg-blue-lt fs-5">
                    <i class="fas fa-layer-group me-1"></i>
                    @if (isset($selectedCluster))
                        {{ $selectedCluster === 'All clusters' ? 'All Clusters' : $clusters->firstWhere('ClusterID', $selectedCluster)->Cluster_Name ?? 'Unknown Cluster' }}
                    @else
                        All Clusters
                    @endif
                </span>
            </p>
            <div class="card">
                <div class="card-body">
                    <div class="timeline">
                        @foreach ($years as $year)
                            <div class="timeline-event">
                                <div class="timeline-event-icon bg-primary-lt">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="card timeline-event-card cursor-pointer year-card"
                                    data-year="{{ $year }}">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ $year }}</h3>
                                        <p class="text-muted">
                                            <i class="fas fa-chart-line me-1"></i>
                                            Performance data for {{ $year }}
                                        </p>
                                        <div class="mt-3">
                                            <span class="badge bg-green-lt me-2">
                                                <i class="fas fa-check me-1"></i>
                                                {{ rand(80, 95) }}% Complete
                                            </span>
                                            <span class="badge bg-yellow-lt">
                                                <i class="fas fa-file-alt me-1"></i>
                                                {{ rand(3, 8) }} Reports
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="year-form" action="{{ route('select-report') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="cluster" value="{{ $selectedCluster }}">
    <input type="hidden" name="year" id="selected-year">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearCards = document.querySelectorAll('.year-card');
        const yearForm = document.getElementById('year-form');
        const selectedYearInput = document.getElementById('selected-year');

        yearCards.forEach(card => {
            card.addEventListener('click', function() {
                const year = this.dataset.year;
                selectedYearInput.value = year;
                yearForm.submit();
            });

            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<style>
    .timeline {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 3rem;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        height: 100%;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-event {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-event-icon {
        position: absolute;
        left: -3.5rem;
        top: 0.5rem;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        text-align: center;
        font-size: 1rem;
        line-height: 2.5rem;
        transition: all 0.3s ease;
    }

    .timeline-event-card {
        transition: all 0.3s ease;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .year-card:hover .timeline-event-icon {
        transform: scale(1.2);
    }

    @media (max-width: 768px) {
        .timeline {
            padding-left: 1.5rem;
        }

        .timeline-event-icon {
            left: -2rem;
            width: 2rem;
            height: 2rem;
            line-height: 2rem;
            font-size: 0.875rem;
        }
    }
</style>
