<div class="page-wrapper">
    <!-- Animated Background -->
    <div class="animated-background"></div>

    <div class="container-xl py-4">
        <div class="row g-4 align-items-center">
            <div class="col-auto">
                <span class="avatar avatar-lg rounded bg-primary-subtle">
                    <i class="fas fa-chart-line fa-lg"></i>
                </span>
            </div>
            <div class="col">
                <h2 class="page-title mb-2">
                    Select Report for {{ $year }}
                </h2>
                <div class="text-muted">
                    {{ $Desc }}
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('select-year') }}" class="btn btn-outline-primary d-none d-sm-inline-block">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Year Selection
                    </a>
                    <a href="{{ route('select-year') }}" class="btn btn-outline-primary d-sm-none btn-icon"
                        aria-label="Back to Year Selection">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card card-lg shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Available Reports</h3>
                        <form action="{{ route('performance-overview') }}" method="POST" class="report-selection-form">
                            @csrf
                            <input type="hidden" name="year" value="{{ $year }}">
                            <div class="mb-4">
                                <div class="form-floating form-floating-custom">
                                    <select class="form-select @error('reportingId') is-invalid @enderror"
                                        id="reportingId" name="reportingId" required>
                                        <option value="">Select a report...</option>
                                        @foreach ($timelines as $timeline)
                                            <option value="{{ $timeline->ReportingID }}"
                                                {{ old('reportingId') == $timeline->ReportingID ? 'selected' : '' }}>
                                                {{ $timeline->ReportName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="reportingId">Report</label>
                                    <div class="form-floating-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                                @error('reportingId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary w-100 btn-pill">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Generate Performance Overview
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}


{{-- @push('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.report-selection-form');
        const select = document.getElementById('reportingId');

        select.addEventListener('change', function() {
            form.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                form.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        });
    });
</script>
{{-- @endpush --}}
