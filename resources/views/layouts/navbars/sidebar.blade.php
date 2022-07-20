<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="./img/logo2.png" alt="AdminLTE Logo" class="brand-image elevation-3 img-circle" style="opacity: .8">
        <span class="brand-text text-white">DEIT - PASCO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Name User -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <label class="d-block m-0">Alexander Pierce</label>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/padron.png') }}" width="30" alt="imagen-pn"></i>
                        <p class="ml-2">Seguimiento <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/metals') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Relación Pacientes Metales Pesados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/homologation') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Homologación</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/user') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Añadir Paciente</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>