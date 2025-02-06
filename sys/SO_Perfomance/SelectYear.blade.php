<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
        <!-- Left side: Decorative section -->
        <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center bg-primary-subtle">
            <div class="text-center">
                <h1 class="display-1 fw-bold text-primary mb-4 animate__animated animate__fadeInUp">ECSA-HC</h1>
                <p class="lead text-muted animate__animated animate__fadeInUp animate__delay-1s">
                    Strategic performance tracking
                </p>
                <div class="mt-5 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="eco-system">
                        <div class="orbit">
                            <div class="planet planet-1"></div>
                            <div class="planet planet-2"></div>
                            <div class="planet planet-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side: Year selection form -->
        <div class="col-lg-7 d-flex align-items-center justify-content-center bg-body-tertiary">
            <div class="w-100 p-4 p-md-5" style="max-width: 420px;">
                <div class="text-end mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        <label class="form-check-label" for="darkModeSwitch">
                            <i class="ti ti-moon"></i>
                        </label>
                    </div>
                </div>
                <h2 class="display-6 fw-bold mb-4 text-center animate__animated animate__fadeInDown">
                    Select Reporting Year
                </h2>
                <p class="text-muted text-center mb-5 animate__animated animate__fadeInDown animate__delay-1s">
                    Choose a year to view strategic objective performance
                </p>
                <form id="yearSelectForm" class="animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="mb-4">
                        <select id="yearSelect" class="form-select form-select-lg" required>
                            <option value="" selected disabled>Select a year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3" disabled>
                            Continue
                            <i class="ti ti-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
    }

    body {
        font-family: 'Inter', sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .dark-mode {
        background-color: #1a202c;
        color: #e2e8f0;
    }

    .eco-system {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
    }

    .orbit {
        width: 100%;
        height: 100%;
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: orbit 20s linear infinite;
    }

    .planet {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -10px 0 0 -10px;
    }

    .planet-1 {
        background-color: var(--primary-color);
        animation: planet1 8s linear infinite;
    }

    .planet-2 {
        background-color: var(--success-color);
        animation: planet2 12s linear infinite;
    }

    .planet-3 {
        background-color: var(--warning-color);
        animation: planet3 16s linear infinite;
    }

    @keyframes orbit {
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes planet1 {
        0% {
            transform: rotate(0deg) translateX(60px) rotate(0deg);
        }

        100% {
            transform: rotate(360deg) translateX(60px) rotate(-360deg);
        }
    }

    @keyframes planet2 {
        0% {
            transform: rotate(0deg) translateX(90px) rotate(0deg);
        }

        100% {
            transform: rotate(360deg) translateX(90px) rotate(-360deg);
        }
    }

    @keyframes planet3 {
        0% {
            transform: rotate(0deg) translateX(40px) rotate(0deg);
        }

        100% {
            transform: rotate(360deg) translateX(40px) rotate(-360deg);
        }
    }

    .form-select {
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(105, 136, 228, 0.5);
    }

    @media (max-width: 991.98px) {
        .row {
            flex-direction: column;
        }

        .col-lg-5 {
            display: none !important;
        }

        .col-lg-7 {
            min-height: 100vh;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeSwitch = document.getElementById('darkModeSwitch');
        const body = document.body;
        const yearSelect = document.getElementById('yearSelect');
        const yearSelectForm = document.getElementById('yearSelectForm');
        const submitButton = yearSelectForm.querySelector('button[type="submit"]');

        // Dark mode toggle
        darkModeSwitch.addEventListener('change', function() {
            body.classList.toggle('dark-mode', this.checked);
        });

        // Enable/disable submit button based on selection
        yearSelect.addEventListener('change', function() {
            submitButton.disabled = !this.value;
        });

        // Form submission
        yearSelectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedYear = yearSelect.value;
            if (selectedYear) {
                window.location.href = `/Ecsa_SO_selectReport?year=${selectedYear}`;
            }
        });

        // Optional: Add some animation when selecting a year
        yearSelect.addEventListener('change', function() {
            if (this.value) {
                this.animate([{
                        transform: 'scale(1)'
                    },
                    {
                        transform: 'scale(1.05)'
                    },
                    {
                        transform: 'scale(1)'
                    }
                ], {
                    duration: 300,
                    iterations: 1
                });
            }
        });
    });
</script>
