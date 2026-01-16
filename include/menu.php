<nav class="navbar navbar-expand-md fixed-top" style="background-color: #1c3147;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" id="logo">
            <img src="fotos/logo.png" style="height: 90px; width:90px;"/>
        </a>
        <button class="navbar-toggler navbar-dark" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link <?php if ($menu == 'inicio') echo 'active';?>" href="index.php" id="menuInicio" style="color: #DEB459; font-family: Flor;">
                        <h3>Flor de Gimeno</h3>
                    </a>
                </li>

                <!-- Si es cliente -->
                <?php if ($esAdmin==false): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($menu == 'productos') echo 'active';?>" href="productos.php" style="color: #FFFFFF;">
                           Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($menu == 'catalogos') echo 'active';?>" href="catalogos.php" style="color: #FFFFFF;">
                           Catálogos
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Si es administrador -->
                <?php if ($esAdmin==true): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php if ($menu == 'administracion') echo 'active';?>" data-bs-toggle="dropdown" style="color: #FFFFFF;">
                            Administración
                        </a>
                        <ul class="dropdown-menu" style="background-color: #FFFFFF;">
                            <li>
                                <a class="dropdown-item" href="listadoUsuarios.php">
                                    <i class="bi bi-person-fill" style="color: #3d6187;"></i> Usuarios
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listadoProductos.php">
                                    <i class="bi bi-box" style="color: #3d6187;"></i> Productos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listadoPedidos.php">
                                    <i class="bi bi-bag" style="color: #3d6187;"></i> Pedidos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="listadoCatalogos.php">
                                    <i class="bi bi-journal" style="color: #3d6187;"></i> Catálogos
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Carrito y compras -->
                <?php if ($esAdmin==false): ?>
                    <li class="navbar-item <?php if ($menu == 'miCarrito') echo 'active';?>">
                        <a class="nav-link" href="detallesCarrito.php" title="Mi Carrito">
                            <i class="bi bi-cart-fill" style="color: #DEB459;"></i>
                        </a>
                    </li>
                    <li class="navbar-item">
                        <a class="nav-link <?php if ($menu == 'misCompras') echo 'active';?>" href="misCompras.php" style="color: #FFFFFF;">
                           Mis Compras
                        </a>
                    </li>
                <?php endif; ?>
                <!-- Edición de usuario y cerrar sesión -->
                <li class="navbar-item">
                    <a class="nav-link <?php if ($menu == 'usuario') echo 'active';?>" href="formMiCuenta.php" style="color: #FFFFFF;" title="Mi Cuenta">
                        <?= $usuario->nombre ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-x-square-fill" style="color: #DEB459;" title="Cerrar sesión"></i>
                        <span class="d-md-none" style="color: #FFFFFF;">Salir</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
