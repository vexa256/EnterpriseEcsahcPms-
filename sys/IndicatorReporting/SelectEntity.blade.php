<div class="container-fluid p-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-building-flag fa-2x text-primary me-3"></i>
                        <div>
                            <h1 class="h3 mb-1">Select Your Entity</h1>
                            <p class="text-muted mb-0">Choose the entity to report on and view its indicators and
                                timelines.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0">
                    <h2 class="card-title h3">
                        <i class="fas fa-list-ul text-primary me-2"></i>Available Entities
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('reporting.period.select') }}" method="GET" id="entityForm">
                        <div class="mb-4">
                            <select name="entity_id" id="entity_id" class="form-select form-select-lg" required
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Select your entity to proceed">
                                @if (auth()->user()->AccountRole === 'Admin')
                                    <option value="" selected disabled>-- Select an Entity --</option>
                                @endif

                                @foreach ($entities as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right me-2"></i>Continue to Reporting Period
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h3 class="card-title h4 mb-4">
                        <i class="fas fa-info-circle text-info me-2"></i>Why Select an Entity?
                    </h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent">
                            <i class="fas fa-check-circle text-success me-2"></i>Tailored Indicators
                        </li>
                        <li class="list-group-item bg-transparent">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>Specific Timelines
                        </li>
                        <li class="list-group-item bg-transparent">
                            <i class="fas fa-chart-line text-danger me-2"></i>Relevant Analytics
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <small class="text-muted">
                        <i class="fas fa-question-circle me-1"></i>Need help? Contact support at atimothy@ecsahc.org
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        var entitySelect = document.getElementById('entity_id');
        var submitButton = document.querySelector('button[type="submit"]');

        entitySelect.addEventListener('change', function() {
            if (this.value) {
                submitButton.classList.add('animate__animated', 'animate__pulse');
            } else {
                submitButton.classList.remove('animate__animated', 'animate__pulse');
            }
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .form-select {
        border-radius: 1rem;
        border: 2px solid #e9ecef;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #4e54c8;
        box-shadow: 0 0 0 0.25rem rgba(78, 84, 200, 0.25);
    }

    .btn-primary {
        border-radius: 1rem;
        padding: 1rem 2rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }

    .list-group-item {
        border: none;
        padding: 1rem 1.25rem;
    }

    .animate__animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }

    .animate__pulse {
        animation-name: pulse;
    }

    @keyframes pulse {
        from {
            transform: scale3d(1, 1, 1);
        }

        50% {
            transform: scale3d(1.05, 1.05, 1.05);
        }

        to {
            transform: scale3d(1, 1, 1);
        }
    }
</style>
