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
                        <img class="img-fluid" src="<?php echo base_url(); ?>\assets\images\logo6.png">
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
                                <a href="javascript:void(0)" id="Gestion">
                                    <span class="pcoded-micon"><i class="feather icon-check-circle"></i></span>
                                    <span class="pcoded-mtext">Gestión</span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class="Option">
                                        <a href="/Gestion/Reserva" id="Reserva">
                                            <span class="pcoded-mtext">Reservas</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                                if($_SESSION['idPerfil']==2 || $_SESSION['idPerfil']==3){

                                }else{ ?>
                            <li class="Menu1 pcoded-hasmenu">
                                <a href="javascript:void(0)" id="Mantenimiento">
                                    <span class="pcoded-micon"><i class="feather icon-sliders"></i></span>
                                    <span class="pcoded-mtext">Mantenimientos</span>
                                </a>
                                <ul class="pcoded-submenu">

                                     <?php
                                        if($_SESSION['idPerfil']==1){

                                        }else{ ?>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Perfil" id="Perfil">
                                            <span class="pcoded-mtext">Perfiles</span>
                                        </a>
                                    </li>
                                     <li class="Option">
                                        <a href="/Mantenimiento/Usuario" id="Usuario">
                                            <span class="pcoded-mtext">Usuarios</span>
                                        </a>
                                    </li>

                                    <?php  };?>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Medida" id="Medida">
                                            <span class="pcoded-mtext">Medidas</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Grupo" id="Grupo">
                                            <span class="pcoded-mtext">Grupo de Categoria</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Categoria" id="Categoria">
                                            <span class="pcoded-mtext">Categoria</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Producto" id="Producto">
                                            <span class="pcoded-mtext">Productos</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Genero" id="Genero">
                                            <span class="pcoded-mtext">Genero</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Local" id="Local">
                                            <span class="pcoded-mtext">Locales(Proveedores)</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Promocion" id="Promocion">
                                            <span class="pcoded-mtext">Promociones</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Publicidad" id="Publicidad">
                                            <span class="pcoded-mtext">Publicidad</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/Delivery" id="Delivery">
                                            <span class="pcoded-mtext">Precio de Delivery</span>
                                        </a>
                                    </li>
                                    <li class="Option">
                                        <a href="/Mantenimiento/TipoTarjeta" id="TipoTarjeta">
                                            <span class="pcoded-mtext">Tipo de Tarjeta</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                             <?php  };?>
                        </ul>
                    </div>
                </nav>
