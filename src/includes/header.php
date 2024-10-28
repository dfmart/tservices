<?php
if (empty($_SESSION['active'])) {
    header('Location: ../');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Panel de Administración</title>
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-color="Silver" data-background-color="blue" data-image="../assets/img/sidebar.jpg">
            <div class="logo bg-primary"><a href="./" class="simple-text logo-normal">
                    Sistemas Inventario
                </a></div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                <li class="nav-item">
                        <a class="nav-link d-flex" href="clientes.php">
                            <i class=" fas fa-users mr-2 fa-2x"></i>
                            <p> Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="proveedores.php">
                            <i class="fas fa-users mr-2 fa-2x"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>
                    <!-- Lista desplegable de inventario -->
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="#" data-toggle="collapse" data-target="#inventarioMenu">
                            <i class="fas fa-warehouse mr-2 fa-2x"></i>
                            <p>Inventario</p>
                            <i class="fas fa-angle-down ml-auto"></i>
                        </a>
                        <ul class="collapse treeview-menu" id="inventarioMenu">
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="registro_partes.php">
                                    <i class="fas fa-puzzle-piece mr-2 fa-2x"></i>
                                    <p>Partes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="registro_escritorio.php">
                                    <i class="fas fa-desktop mr-2 fa-2x"></i>
                                    <p>Escritorio</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="registro_portatil.php">
                                    <i class="fas fa-laptop mr-2 fa-2x"></i>
                                    <p>Portátil</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="registro_monitor.php">
                                    <i class="fas fa-tv mr-2 fa-2x"></i>
                                    <p>Monitor</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex" href="registro_servidor.php">
                                    <i class="fas fa-server mr-2 fa-2x"></i>
                                    <p>Servidor</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Lista desplegable de configuración -->
                    <li class="nav-item">
                        <a class="nav-link d-flex text-white" href="#" data-toggle="collapse" data-target="#configuracionMenu">
                            <i class="fas fa-cogs mr-2 fa-2x"></i>
                            <p>Configuración</p>
                            <i class="fas fa-angle-down ml-auto"></i>
                        </a>
                        <ul class="collapse treeview-menu" id="configuracionMenu">
                            <li class="nav-item">
                                <a class="nav-link d-flex text-white" href="config.php">
                                    <i class="fas fa-cogs mr-2 fa-2x"></i>
                                    <p>Datos de la empresa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex text-white" href="usuarios.php">
                                    <i class="fas fa-user mr-2 fa-2x"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex text-white" href="tipo.php">
                                    <i class="fas fa-tags mr-2 fa-2x"></i>
                                    <p>Tipos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex text-white" href="permiso.php">
                                    <i class="fas fa-tv mr-2 fa-2x"></i>
                                    <p>Permisos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="javascript:;">Inventario</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">

<ul class="navbar-nav">
<li class="nav-item dropdown">
        <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user" style="font-size: 2em;"></i>
        <p class="d-lg-none d-md-block">
                Cuenta
            </p>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#nuevo_pass">Restablecer Password</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="salir.php">Cerrar Sesión</a>
        </div>
    </li>
</ul>
</div>
</div>
</nav>
<!-- End Navbar -->
<div class="content bg">
<div class="container-fluid">

