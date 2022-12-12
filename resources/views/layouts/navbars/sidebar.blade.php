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
                        <i><img src="{{ asset('./img/menu/fed1.png') }}" width="30" alt="imagen-fed"></i>
                        <p class="ml-2">FED <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-male nav-icon"></i>
                                <p>Niños</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/premature') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Niños Prematuros (CG03)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/tmz') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tamizaje Neonatal (CG02)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/supplementation') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Niños 4 Meses (CG04)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/iniOport') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Incio Oportuno (CG05)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/cred') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cred Avance Mensual (CG06)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/childPackage') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Paquete Completo</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-female nav-icon"></i>
                                <p>Gestantes</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/bateria') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bateria Completa (CG01)</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/tratamiento') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tmz e Inicio de Tratamiento por Violencia</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/newUsers') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usuarias Nuevas con Tmz de Violencia (GG-VI02)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-user-nurse nav-icon"></i>
                                <p>Medicamentos</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('/professionals') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cantidad de Profesionales EPP(2020 FED)</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/sisCovid') }}" class="nav-link">
                                <i class="fa fa-user-nurse nav-icon"></i>
                                <p>Sis-Covid</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/tracing.png') }}" width="30" alt="imagen-seg"></i>
                        <p class="ml-2">Seguimiento <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/patient') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Detalle Paciente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Niños</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Padrón Nominal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Desparasitación</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Homologación</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/filePlane') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Archivos Planos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Promsa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>R40</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/indicartors.png') }}" width="30" alt="imagen-ind"></i>
                        <p class="ml-2">Indicadores Diresa <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/conventions') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Convenios de Gestión</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Programas Presupuestales</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/covid.png') }}" width="30" alt="imagen-covid"></i>
                        <p class="ml-2">Covid 19 <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Consentimiento</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Consulta de Vacunación</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Consulta Padrón</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Vacunación Covid</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Consolidado Vacuna</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/padron.png') }}" width="30" alt="imagen-pn"></i>
                        <p class="ml-2">Padrón Nominal <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Padrón Niños</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Padrón Gestantes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i><img src="{{ asset('./img/menu/analytics.png') }}" width="30" alt="imagen-analy"></i>
                        <p class="ml-2">Tablero <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tablero</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Avance Convenios de Gestión</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>