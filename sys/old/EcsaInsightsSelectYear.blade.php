<div class="animated-background"></div>

<div class="container-xl py-4">
    <div class="row g-4 align-items-center">
        <div class="col-auto">
            <span class="avatar avatar-lg rounded bg-primary-subtle">
                <i class="fas fa-calendar-alt fa-lg"></i>
            </span>
        </div>
        <div class="col">
            <h2 class="page-title mb-2">
                Select Reporting Year
            </h2>
            <div class="text-muted">
                {{ $Desc }}
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8 col-lg-6 mx-auto">
            <div class="card card-lg shadow-lg">
                <div class="card-body">
                    <h3 class="card-title mb-4">Choose a Year</h3>
                    <form action="{{ route('select-report') }}" method="POST" class="year-selection-form">
                        @csrf
                        <div class="mb-4">
                            <div class="form-floating form-floating-custom">
                                <select class="form-select @error('year') is-invalid @enderror" id="year"
                                    name="year" required>
                                    <option value="">Select a year...</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ old('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="year">Reporting Year</label>
                                <div class="form-floating-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100 btn-pill">
                                <i class="fas fa-arrow-right me-2"></i>
                                Continue to Report Selection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animated-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(45deg, #f3f3f3, #e9ecef);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .form-floating-custom {
        position: relative;
    }

    .form-floating-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .btn-pill {
        border-radius: 50px;
    }

    .year-selection-form {
        transition: all 0.3s ease;
    }

    .year-selection-form:hover {
        transform: translateY(-5px);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.year-selection-form');
        const select = document.getElementById('year');

        select.addEventListener('change', function() {
            form.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                form.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        });
    });
</script>
