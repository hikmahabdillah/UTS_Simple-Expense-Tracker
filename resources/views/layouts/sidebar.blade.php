<div class="sidebar w-full">
    <!-- SidebarSearch Form -->
    {{-- <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div> --}}

    <!-- Sidebar Menu -->
    <nav class="mt-2 font-poppins font-medium">
        <ul class="nav nav-pills nav-sidebar flex-column gap-2" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}"
                    class="nav-link !py-3 w-full animate-fade-right animate-once animate-duration-1000 {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-trend-up fa-xl pr-2"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/') }}"
                    class="nav-link !py-3 w-full animate-fade-right animate-once animate-duration-1000 {{ $activeMenu == 'expenses' ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-transfer fa-lg pr-2"></i>
                    <p>Data Expense</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
