<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
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
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card card-md">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Choose a Year</h3>
                            <form action="{{ route('select-report') }}" method="POST">
                                @csrf
                                <div class="mb-3">
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
    </div>
</div>

<style>
    .form-floating-custom {
        position: relative;
    }

    .form-floating-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }

    .btn-pill {
        border-radius: 50px;
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
        const form = document.querySelector('form');
        const select = document.getElementById('year');

        select.addEventListener('change', function() {
            form.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                form.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        });
    });
</script>
