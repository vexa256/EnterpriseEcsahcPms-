<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Select Reporting Year
                </h2>
                <div class="text-muted mt-1">Choose a year to view cluster performance breakdown</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-6 col-lg-4 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Available Years</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Select a Year</label>
                            <select class="form-select" id="yearSelect">
                                <option value="">Choose a year...</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" id="continueBtn" disabled>
                            Continue
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('yearSelect');
        const continueBtn = document.getElementById('continueBtn');

        yearSelect.addEventListener('change', function() {
            continueBtn.disabled = !this.value;
        });

        continueBtn.addEventListener('click', function() {
            const selectedYear = yearSelect.value;
            if (selectedYear) {
                window.location.href = "{{ route('Ecsa_CP_selectReport') }}?year=" + selectedYear;
            }
        });
    });
</script>
