<div class="sticky-top">
    <header class="navbar navbar-expand-md sticky-top d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="/">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSprutZGJpPgDTYg_gFf3qAxKeriNs6Wma7_w&s"
                        alt="Logo" width="110" height="32" class="navbar-brand-image">
                </a>
            </div>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item d-none d-md-flex me-3">
                    <div class="btn-list">
                        <a href="{{ url('/') }}" class="btn btn-5" target="_blank" rel="noreferrer">
                            <!-- Replacing the brand-github SVG with FontAwesome -->
                            <i class="fa-brands fa-github icon icon-2"></i>
                            ECSA-HC Dashboard
                        </a>
                        <a href="#" class="btn btn-6" target="_blank" rel="noreferrer">
                            <!-- Replacing the heart SVG with FontAwesome -->
                            <i class="fa-solid fa-heart text-pink icon icon-2"></i>
                            MPA Dashboard
                        </a>
                    </div>
                </div>
                <div class="d-none d-md-flex">


                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                style="background-image: url('https://www.svgrepo.com/show/286578/users-young.svg')"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="mt-1 small text-secondary">
                                    {{ Auth::user()->JobTitle }}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @include('nav.nav')
</div>
