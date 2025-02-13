<div class="container-xl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-lg border-0 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4 animate__animated animate__fadeInDown">
                        <span class="text-gradient">Select Reporting Year</span>
                    </h2>
                    @if (isset($reportType) && $reportType)
                        <div class="alert alert-info mb-4 animate__animated animate__fadeIn">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                        <path d="M12 9h.01"></path>
                                        <path d="M11 12h1v4h1"></path>
                                    </svg>
                                </div>
                                <div class="ms-3">
                                    Selected Report Type: <strong>{{ $reportType }}</strong>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4 animate__animated animate__fadeIn">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 9v2m0 4v.01"></path>
                                        <path
                                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ms-3">
                                    No report type selected. Please go back and select a report type.
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('rrf.report.dashboard') }}" method="POST" id="yearSelectForm">
                        @csrf
                        <input type="hidden" name="report_type" value="{{ $reportType ?? '' }}">
                        <div class="mb-4 position-relative">
                            <label for="reporting_year" class="form-label visually-hidden">Reporting Year</label>
                            <select name="reporting_year" id="reporting_year" class="form-select form-select-lg"
                                required>
                                <option value="" selected disabled>Choose a reporting year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            <div class="select-arrow"></div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit"
                                class="btn btn-primary btn-lg animate__animated animate__pulse animate__infinite">
                                <span class="btn-label">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-chart-dots" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 3v18h18"></path>
                                        <path d="M9 9m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M19 7m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M14 15m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M10.16 10.62l2.34 2.88"></path>
                                        <path d="M15.088 13.328l2.837 -4.586"></path>
                                    </svg>
                                </span>
                                Generate Dashboard
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .text-gradient {
        background: linear-gradient(45deg, #12c2e9, #c471ed, #f64f59);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }

    .select-arrow {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid #6c757d;
        pointer-events: none;
    }

    #reporting_year {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #reporting_year:hover,
    #reporting_year:focus {
        box-shadow: 0 0 15px rgba(18, 194, 233, 0.5);
    }

    .btn-primary {
        background: linear-gradient(45deg, #12c2e9, #c471ed, #f64f59);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .alert-info {
        background: linear-gradient(45deg, #e0f7fa, #b2ebf2);
        border: none;
        color: #006064;
    }
</style>
{{-- 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('reporting_year');
        const form = document.getElementById('yearSelectForm');

        select.addEventListener('change', function() {
            select.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                select.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (select.value) {
                this.classList.add('animate__animated', 'animate__fadeOutUp');
                setTimeout(() => {
                    this.submit();
                }, 500);
            }
        });
    });
</script> --}}
