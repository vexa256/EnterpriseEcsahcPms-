<div class="container-fluid p-0" style="height:100% !important; overflow:hidden">
    <div class="row g-0 min-vh-100">
        <div class="col-12 col-md-8 col-lg-6 mx-auto d-flex align-items-center justify-content-center">
            <div class="w-100 p-4 p-md-5 bg-white shadow-sm rounded-3">
                <div class="text-center mb-4">
                    <h1 class="h2 fw-bold text-primary">ECSA-HC</h1>
                    <p class="text-muted">Strategic Performance Tracking</p>
                </div>

                <h2 class="h4 fw-bold mb-4 text-center">Select Report for {{ $selectedYear }}</h2>

                <form id="reportSelectForm">
                    <div class="mb-4">
                        <label for="reportSelect" class="form-label">Choose a report:</label>
                        <div class="position-relative">
                            <select id="reportSelect" class="form-select form-select-lg" required>
                                <option value="" selected disabled>Select a report</option>
                                @foreach ($reports as $report)
                                    <option value="{{ $report->ReportingID }}">{{ $report->ReportName }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down position-absolute end-0 top-50 translate-middle-y me-3"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="{{ route('Ecsa_SO_selectYear') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2" disabled>
                            Continue<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3">
    <button id="darkModeToggle" class="btn btn-light rounded-circle shadow-sm">
        <i class="bi bi-moon"></i>
    </button>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");

    :root {
        --primary-color: #0056b3;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--light-color);
        color: var(--dark-color);
        transition: background-color 0.3s ease, color 0.3s ease;

    }

    .dark-mode {
        background-color: var(--dark-color);
        color: var(--light-color);
    }

    .dark-mode .bg-white {
        background-color: #2c3e50 !important;
    }

    .dark-mode .text-muted {
        color: #a0aec0 !important;
    }

    .dark-mode .btn-outline-secondary {
        color: var(--light-color);
        border-color: var(--light-color);
    }

    .dark-mode .form-select {
        background-color: #34495e;
        color: var(--light-color);
        border-color: #4a5568;
    }

    .form-select {
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background-color: #004494;
        border-color: #004494;
    }

    .btn-outline-secondary:hover {
        background-color: var(--secondary-color);
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;
        const reportSelect = document.getElementById('reportSelect');
        const reportSelectForm = document.getElementById('reportSelectForm');
        const submitButton = reportSelectForm.querySelector('button[type="submit"]');

        // Dark mode toggle
        darkModeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            this.querySelector('i').classList.toggle('bi-moon');
            this.querySelector('i').classList.toggle('bi-sun');
        });

        // Enable/disable submit button based on selection
        reportSelect.addEventListener('change', function() {
            submitButton.disabled = !this.value;
        });

        // Form submission
        reportSelectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedReport = reportSelect.value;
            if (selectedReport) {
                window.location.href =
                    `/Ecsa_SO_showPerformance?year={{ $selectedYear }}&report=${selectedReport}`;
            }
        });

        // Subtle animation on select focus
        reportSelect.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.3s ease';
        });

        reportSelect.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>
