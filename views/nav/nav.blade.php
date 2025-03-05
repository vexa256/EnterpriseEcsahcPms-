<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        @php
            $isAdmin = auth()->check() && auth()->user()->AccountRole === 'Admin';
            $userType = auth()->check() ? auth()->user()->UserType : null;
            $isSpecialUser = auth()->check() && auth()->user()->email === 'herrp@ecsahc.org';
        @endphp
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <!-- Home (Visible to everyone) -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="fa-solid fa-house"></i>
                                    </span>
                                    <span class="nav-link-title">Home</span>
                                </a>
                            </li>

                            <!-- MPA Sections (Visible to Admins and Special User) -->
                            @if ($isAdmin || $isSpecialUser)
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
                                                <a class="dropdown-item" href="{{ route('MgtEntities') }}">MPA
                                                    Entities</a>
                                                <a class="dropdown-item" href="{{ route('MgtClusters') }}">MPA
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
                                                <a class="dropdown-item" href="{{ route('MgtMpaTimelines') }}">MPA
                                                    Timelines</a>
                                                <a class="dropdown-item" href="{{ route('MgtEcsaTimelines') }}">MPA
                                                    Timelines</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('MgtEcsaTimelinesStatus') }}">MPA Timelines
                                                    Status</a>
                                                <a class="dropdown-item" href="{{ route('MgtMpaTimelinesStatus') }}">MPA
                                                    Timelines Status</a>
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
                                        <span the="nav-link-title">User Settings</span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                <a class="dropdown-item" href="{{ route('MgtMpaUsers') }}">MPA Users</a>
                                                <a class="dropdown-item" href="{{ route('MgtEcsaUsers') }}">MPA
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
                                                <a class="dropdown-item" href="{{ route('MgtSO') }}">MPA Strategic
                                                    Objectives</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('mpaIndicators.SelectEntity') }}">MPA CRF
                                                    Indicators</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('mpaRRF.ShowRRFIndicators') }}">MPA RRF
                                                    Indicators</a>
                                                <a class="dropdown-item" href="{{ route('SelectSo') }}">MPA
                                                    Indicators</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <!-- File Report (Visible to Admins and Special User) -->
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
                                            @if ($isAdmin || $isSpecialUser)
                                                <a class="dropdown-item" href="{{ route('entity.select') }}">MPA
                                                    Report on Indicators</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Hide ECSA-HC specific sections for Special User -->
                            @if (!($isAdmin || $isSpecialUser))
                                <!-- Analytics and Report (Visible to Admins and ECSA-HC users) -->
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
                                                @if ($userType === 'ECSA-HC')
                                                    <a class="dropdown-item"
                                                        href="{{ route('Reportselectcluster') }}">ECSA-HC Indicators
                                                        Perfomance</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('Ecsa_SO_selectYear') }}">ECSA-HC SO
                                                        Perfomance</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('Ecsa_CP_selectYear') }}">ECSA-HC Cluster
                                                        Perfomance</a>
                                                @endif
                                            </div </div>
                                        </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
