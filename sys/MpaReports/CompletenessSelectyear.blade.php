{{-- resources/views/MpaReports/CompletenessSelectyear.blade.php --}}

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <h2 class="card-title text-center mb-4 text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Select Reporting Year
                        </h2>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('mpa.reports.completeness.index') }}" method="GET" id="yearSelectForm">
                            <div class="mb-4">
                                <select name="reporting_year" id="reporting_year" class="form-select form-select-lg"
                                    required>
                                    <option value="" selected disabled>Choose reporting year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-chart-line me-2"></i>Generate Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('yearSelectForm');
        const yearSelect = document.getElementById('reporting_year');

        yearSelect.addEventListener('change', function() {
            if (this.value) {
                form.submit();
            }
        });

        form.addEventListener('submit', function(event) {
            if (!yearSelect.value) {
                event.preventDefault();
                yearSelect.classList.add('is-invalid');
            }
        });
    });
</script>
