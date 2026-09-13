      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">
                  <span class="brand-text fw-light">Cafe POS</span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2" aria-label="Main navigation">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-cup-hot-fill"></i>
                  <p>Open POS Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-receipt"></i>
                  <p>Live Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Products.index') }}" class="nav-link {{ request()->routeIs('Products.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box-seam"></i>
                  <p>Products & Modifiers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Categories.index') }}" class="nav-link {{ request()->routeIs('Categories.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people-fill"></i>
                  <p>Categories</p>
                </a>
              </li>
              @if (auth()->user()->role === 'admin')
                <li class="nav-item">
                  <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-badge-fill"></i>
                    <p>Employee Accounts</p>
                  </a>
                </li>
              @endif
              <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>Profile</p>
                </a>
              </li>
            </ul>
            <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
              <a
                href="{{ route('logout') }}"
                class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
                onclick="event.preventDefault(); this.closest('nav').querySelector('#sidebar-logout').submit();"
              >
                <i class="bi bi-lock" aria-hidden="true"></i>
                Logout
              </a>
              <form id="sidebar-logout" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
              </form>
            </div>
          </nav>
        </div>
      </aside>