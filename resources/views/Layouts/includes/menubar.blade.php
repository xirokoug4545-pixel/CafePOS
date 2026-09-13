<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                class="nav-link"
                data-lte-toggle="sidebar"
                href="#"
                role="button"
                aria-label="Toggle sidebar"
                >
                <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <form class="d-flex" role="search">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-navbar" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <!-- Removed image, added dynamic user name -->
                    <span class="d-none d-md-inline fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <p class="mt-3">
                            {{ Auth::user()->name }}
                            <small class="text-capitalize">Role: {{ str_replace('_', ' ', Auth::user()->role) }}</small>
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Profile</a>
                        
                        <!-- Laravel POST Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger float-end">Sign out</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>