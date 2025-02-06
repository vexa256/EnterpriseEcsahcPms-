<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <!-- Home -->
                            <li class="nav-item">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-house"></i>
                                    </span>
                                    <span class="nav-link-title">Home</span>
                                </a>
                            </li>

                            @if (auth()->check() && auth()->user()->AccountRole === 'Admin')
                                <!-- Entities And Clusters -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-sitemap"></i>
                                        </span>
                                        <span class="nav-link-title">Entities And Clusters</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                {{-- <a class="dropdown-item" href="{{ route('MgtEntities') }}">MPA
                                                    Entities</a> --}}
                                                <a class="dropdown-item" href="{{ route('MgtClusters') }}">ECSA-HC
                                                    Clusters</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Reporting Timelines -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-calendar"></i>
                                        </span>
                                        <span class="nav-link-title">Reporting Timelines</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                {{-- <a class="dropdown-item" href="{{ route('MgtMpaTimelines') }}">MPA
                                                    Timelines</a> --}}
                                                <a class="dropdown-item" href="{{ route('MgtEcsaTimelines') }}">ECSA-HC
                                                    Timelines</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('MgtEcsaTimelinesStatus') }}">ECSA Timelines
                                                    Status</a>
                                                {{-- <a class="dropdown-item" href="{{ route('MgtMpaTimelinesStatus') }}">MPA
                                                    Timelines Status</a> --}}
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- User Settings -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-user-gear"></i>
                                        </span>
                                        <span class="nav-link-title">User Settings</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                {{-- <a class="dropdown-item" href="{{ route('MgtMpaUsers') }}">MPA Users</a> --}}
                                                <a class="dropdown-item" href="{{ route('MgtEcsaUsers') }}">ECSA-HC
                                                    Users</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- Indicators -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <i class="fa-solid fa-chart-line"></i>
                                        </span>
                                        <span class="nav-link-title">Indicators</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                <a class="dropdown-item" href="{{ route('MgtSO') }}">ECSA-HC Strategic
                                                    Objectives</a>
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('mpaIndicators.SelectEntity') }}">MPA CRF
                                                    Indicators</a> --}}
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('mpaRRF.ShowRRFIndicators') }}">MPA RRF
                                                    Indicators</a> --}}
                                                <a class="dropdown-item" href="{{ route('SelectSo') }}">ECSA-HC
                                                    Indicators</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <!-- File Report (visible to all) -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-file-export"></i>
                                    </span>
                                    <span class="nav-link-title">File Report</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('Ecsa_SelectUser') }}">ECSA-HC
                                                Reports</a>
                                            {{-- <a class="dropdown-item" href="{{ route('Ecsa_SelectUser') }}">MPA
                                                Reports</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Analytics and Report (visible to all) -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-chart-pie"></i>
                                    </span>
                                    <span class="nav-link-title">Analytics and Report</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('Reportselectcluster') }}">ECSA-HC
                                                Indicators
                                                Perfomance</a>
                                            <a class="dropdown-item" href="{{ route('Ecsa_SO_selectYear') }}">ECSA-HC
                                                SO Perfomance</a>
                                            <a class="dropdown-item" href="{{ route('Ecsa_CP_selectYear') }}">ECSA-HC
                                                Cluster Perfomance</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
