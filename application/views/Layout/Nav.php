<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <nav class="navbar header-navbar pcoded-header" header-theme="theme5">
            <div class="navbar-wrapper">

                <div class="navbar-logo">
                    <a class="mobile-menu" id="mobile-collapse" href="#!">
                        <i class="feather icon-menu"></i>
                    </a>
                    <a href="/Menu">
                        <!-- Medida de 150 x 30 para que encaje -->
                        <img class="img-fluid" src="<?php echo base_url(); ?>\assets\images\logo6.png" alt="Theme-Logo">
                    </a>
                    <a class="mobile-options">
                        <i class="feather icon-more-horizontal"></i>
                    </a>
                </div>

                <div class="navbar-container container-fluid">
                    <ul class="nav-left">
                        <li>
                            <a href="#!" onclick="javascript:toggleFullScreen()">
                                <i class="feather icon-maximize full-screen"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav-right">
                        <li class="user-profile header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    <?php
                                     if(isset($_SESSION['NombreUsuario'])){
                                    ?>

                                    <?php if($_SESSION['Imagen']!=null){ ?>
                                    <a href="../../assets/images/<?php echo $_SESSION['Imagen']?>" data-lightbox="1" data-title="<?php echo $_SESSION['NombreUsuario']?>">
                                        <img src="../../assets/images/<?php echo $_SESSION['Imagen']?>" class="img-fluid rounded img-20">
                                    </a>
                                    <span>
                                        <?php } ?>

                                        <?php echo  $_SESSION['NombreUsuario']; ?></span>
                                    <?php
                                     }else{
                                     } 
                                    ?>
                                    <i class="feather icon-chevron-down"></i>
                                </div>
                                <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                    <li>
                                        <a href="javascript:void(0)" onclick="CerrarSession()">
                                            <i class="feather icon-log-out text-danger" title="Cerrar Sesión"></i> Salir
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- MENU -->
        <div class="pcoded-main-container">
            <input type="hidden" id="idPerfilUsuario" value="<?php echo $_SESSION['Perfil'];?>">
            <input type="hidden" id="idUsuario" value="<?php echo $_SESSION['idLogin'];?>">
            <div class="pcoded-wrapper">
                <nav class="pcoded-navbar">
                    <div class="pcoded-inner-navbar main-menu">
                        <div class="pcoded-navigatio-lavel">Menú Principal</div>
                        <ul class="pcoded-item pcoded-left-item">
                            <li class="Menu1 pcoded-hasmenu">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                    <span class="pcoded-mtext">Gestión</span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class="Menu2 pcoded-hasmenu">
                                        <a href="javascript:void(0)">
                                            <span class="pcoded-mtext">General</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li class="Option">
                                                <a href="/Gestion/Ubigeo">
                                                    <span class="pcoded-mtext">Ubigeo Usuarios</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="pcoded-submenu">
                                    <li class="Menu2 pcoded-hasmenu">
                                        <a href="javascript:void(0)">
                                            <span class="pcoded-mtext">Captación</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li class="Option">
                                                <a href="/Gestion/DiagnosticoIndividual">
                                                    <span class="pcoded-mtext">Diagnosticos Invidual</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Gestion/DiagnosticoGrupal">
                                                    <span class="pcoded-mtext">Diagnostico Grupal</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Gestion/DiagnosticoResultados">
                                                    <span class="pcoded-mtext">Resultados</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="Menu1 pcoded-hasmenu">
                                <a href="javascript:void(0)" id="Mantenimiento">
                                    <span class="pcoded-micon"><i class="feather icon-sidebar"></i></span>
                                    <span class="pcoded-mtext">Mantenimientos</span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class="Menu2 pcoded-hasmenu">
                                        <a href="javascript:void(0)" id="General">
                                            <span class="pcoded-mtext">General</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li class="Option">
                                                <a href="/Mantenimiento/General/Usuario" id="Usuario">
                                                    <span class="pcoded-mtext">Usuarios</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Mantenimiento/General/Perfil" id="Perfil">
                                                    <span class="pcoded-mtext">Perfiles</span>
                                                </a>
                                            </li>
                                            <li class="Option" >
                                                <a href="/Mantenimiento/General/Area" id="Area">
                                                    <span class="pcoded-mtext">Áreas de Gestión</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Mantenimiento/General/Postulante" id="Postulante">
                                                    <span class="pcoded-mtext">Postulantes</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="pcoded-submenu">
                                    <li class="Menu2 pcoded-hasmenu">
                                        <a href="javascript:void(0)" id="Captacion">
                                            <span class="pcoded-mtext">Captación</span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li class="Option">
                                                <a href="/Mantenimiento/Captacion/CopaOro" id="CopaOro">
                                                    <span class="pcoded-mtext">Copa Oro</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Mantenimiento/Captacion/CopaPlata" id="CopaPlata">
                                                    <span class="pcoded-mtext">Copa Plata</span>
                                                </a>
                                            </li>
                                            <li class="Option">
                                                <a href="/Mantenimiento/Captacion/Posicion" id="Posicion">
                                                    <span class="pcoded-mtext">Posiciones</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
