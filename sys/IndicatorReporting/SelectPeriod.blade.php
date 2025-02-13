<div class="container-fluid p-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-cool">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white p-3 me-4">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h1 class="h3 mb-1 text-white">Reporting Timeline</h1>
                            <p class="text-white-50 mb-0">Select the reporting period for {{ $entity->Entity }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm hover-elevate-up">
                <div class="card-body p-4">
                    <h2 class="card-title h4 mb-4">
                        <i class="fas fa-clock text-primary me-2"></i>Choose Reporting Period
                    </h2>
                    <form action="{{ route('indicator.show') }}" method="GET" id="periodForm">
                        <input type="hidden" name="entity_id" value="{{ $entity->EntityID }}">
                        <div class="form-floating mb-4">
                            <select name="reporting_period" id="reporting_period" class="form-select custom-select"
                                required>
                                <option value="" selected disabled>Select Reporting Period</option>
                                @foreach ($reportingPeriods as $period)
                                    <option value="{{ $period->ReportingID }}">{{ $period->ReportName }}
                                        ({{ $period->Year }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="reporting_period">Reporting Period</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" id="proceedButton" class="btn btn-primary btn-lg custom-btn-hover"
                                disabled>
                                <i class="fas fa-chart-line me-2"></i>Proceed to Indicators
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm hover-elevate-up">
                <div class="card-body p-4">
                    <h3 class="card-title h5 mb-4">
                        <i class="fas fa-lightbulb text-warning me-2"></i>Why This Matters
                    </h3>
                    <ul class="timeline-list">
                        <li class="timeline-item">
                            <span class="timeline-point bg-primary"></span>
                            <p class="mb-0">Ensures data consistency across reporting periods</p>
                        </li>
                        <li class="timeline-item">
                            <span class="timeline-point bg-success"></span>
                            <p class="mb-0">Facilitates accurate trend analysis over time</p>
                        </li>
                        <li class="timeline-item">
                            <span class="timeline-point bg-info"></span>
                            <p class="mb-0">Aligns with strategic planning cycles</p>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-light border-top-0 p-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-3 fa-2x"></i>
                        <small class="text-muted">
                            Select the most recent completed period for up-to-date reporting.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-cool {
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
    }

    .hover-elevate-up {
        transition: all 0.3s ease;
    }

    .hover-elevate-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .custom-select {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
    }

    .custom-select:focus {
        border-color: #3a7bd5;
        box-shadow: 0 0 0 0.25rem rgba(58, 123, 213, 0.25);
    }

    .custom-btn-hover {
        transition: all 0.3s ease;
    }

    .custom-btn-hover:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }

    .timeline-list {
        list-style-type: none;
        padding-left: 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 1.5rem;
    }

    .timeline-point {
        position: absolute;
        left: 0;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .form-floating>.form-select {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(58, 123, 213, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(58, 123, 213, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(58, 123, 213, 0);
        }
    }

    .btn-pulse {
        animation: pulse 1.5s infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelect = document.getElementById('reporting_period');
        const proceedButton = document.getElementById('proceedButton');

        function updateButtonState() {
            const isSelected = periodSelect.value !== "";
            proceedButton.disabled = !isSelected;
            if (isSelected) {
                proceedButton.classList.add('btn-pulse');
            } else {
                proceedButton.classList.remove('btn-pulse');
            }
        }

        periodSelect.addEventListener('change', updateButtonState);

        // Initial state check
        updateButtonState();

        // For demonstration purposes, log the current state
        console.log('Initial button state:', proceedButton.disabled ? 'Disabled' : 'Enabled');
        periodSelect.addEventListener('change', function() {
            console.log('Selection changed. Button state:', proceedButton.disabled ? 'Disabled' :
                'Enabled');
        });
    });
</script>
