<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/tipoProducto.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/pedido.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Inicio";
$menu = "inicio";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}


$tipos = TiposProducto::listado();


//Comprueba si hay algún carrito activo para este usuario y si no lo hay lo crea
$carrito = Carrito::comprobarCarrito($usuario->idUsuario);


//Conseguir datos para el gráfico
$ventasPorCategoria = Pedido::listadoGrafico(date('Y-m-d'));
$datosVentasJSON = json_encode($ventasPorCategoria);
    

include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<!--Carrusel de imágenes-->
<div style="margin-top: 5px;">
    <div id="carouselPrincipal" class="carousel slide" data-bs-ride="carousel" style="height: 450px;">
        <div class="carousel-inner" style="height: 450px;">
            <div class="carousel-item active">
                <img src="fotos/carrusel1.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="fotos/carrusel2.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="fotos/carrusel3.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="fotos/carrusel4.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselPrincipal" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#carouselPrincipal" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
    </div>
</div>


<?php if($esAdmin == false) :?>
    <!-- Categorías de productos -->
    <div class="row row-cols-lg-5 row-cols-md-3 row-cols-sm-2 categoria-productos">
        <?php foreach($tipos as $tipo) : ?>
            <a href="productos.php?idTipo=<?= $tipo->idTipo ?>">
                <div class="col">
                    <div class="card mb-4 btn" id="categoria">
                        <img src="fotos/<?=e($tipo->imagen)?>" class="card-img-top mx-auto mt-3" id="categoria-fotoynombre"/>
                        <div class="card-body" id="categoria-fotoynombre">
                            <div class="col-md-12 text-center">
                                <p class="nombre-categoria">
                                    <?=e($tipo->nombre)?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else:?>
    <!-- Categorías de administración -->
    <div class="row row-cols-lg-5 row-cols-md-3 row-cols-sm-2 categoria-administracion">
        <a href="listadoUsuarios.php">
            <div class="col">
                <div class="card mb-4 btn">
                    <i class="bi bi-person-fill fs-1"></i>
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <p class="nombre-categoria">Usuarios</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="listadoProductos.php">
            <div class="col">
                <div class="card mb-4 btn">
                    <i class="bi bi-box fs-1"></i>
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <p class="nombre-categoria">Productos</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="listadoPedidos.php">
            <div class="col">
                <div class="card mb-4 btn">
                    <i class="bi bi-bag fs-1"></i>
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <p class="nombre-categoria">Pedidos</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="listadoCatalogos.php">
            <div class="col">
                <div class="card mb-4 btn">
                    <i class="bi bi-journal fs-1"></i>
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <p class="nombre-categoria">Catálogos</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?php endif;?>


<?php if($esAdmin == false) :?>
    <!--Tutorial cuidado joyas-->
    <div class="cuidado-joyas">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4 imagen">
                    <img src="fotos/cuidadoJoyas.jpg" class="img-fluid rounded" alt="...">
                </div>
                <div class="col-md-8 texto">
                    <div class="card-body">
                        <h3 class="card-title">Tutorial de Cuidado de Joyas</h3>
                        <ul class="card-text">
                            <li id="listaCuidado"><strong>Limpieza delicada:</strong> Limpia tus joyas con un paño suave ligeramente humedecido para eliminar restos de suciedad y mantener su brillo natural.</li>
                            <li id="listaCuidado"><strong>Evita productos químicos:</strong> Procura que las joyas no entren en contacto con perfumes, cremas, lociones o productos químicos agresivos, ya que pueden deteriorar los metales y las piedras.</li>
                            <li id="listaCuidado"><strong>Limpieza profunda:</strong> Para una limpieza más completa, introduce las joyas en agua tibia con un jabón neutro y frótalas suavemente con un cepillo de cerdas blandas.</li>
                            <li id="listaCuidado"><strong>Secado y conservación:</strong> Sécalas cuidadosamente tras la limpieza y guárdalas en un lugar fresco, seco y protegido para prevenir la oxidación y conservarlas en perfecto estado.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!--Gráfico ventas por categoría en el día actual-->
    <div class="grafico-ventas">
        <div class="card mb-3">
            <h5>VENTAS POR CATEGORÍA HOY</h5>
            <canvas id="graficoVentas" width="400" height="200"></canvas>
        </div>
    </div>
<?php endif;?>


<?php include __DIR__.'/include/scripts.php';?>
    
    <!--Carrusel-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!--Gráfico-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Obtener el elemento canvas del gráfico
        var ctx = document.getElementById('graficoVentas').getContext('2d');

        // Configurar los datos del gráfico
        var datos = <?php echo $datosVentasJSON; ?>;

        // Configurar las etiquetas del eje X (semanas)
        var etiquetas = [];
        // Configurar los valores del eje Y (ventas)
        var ventas = [];

        // Asignar valores
        datos.forEach(function(venta) {
            ventas.push(venta.total);
            etiquetas.push(venta.tipo);
        });

        // Crear un array de colores
        var colores = ['rgba(11, 28, 45, 0.9)',
                        'rgba(16, 42, 67, 0.9)',
                        'rgba(21, 46, 108, 0.85)',
                        'rgba(33, 64, 130, 0.85)',
                        'rgba(201, 162, 77, 0.85)'];


        // Crear un array de bordes
        var bordes = ['rgba(234, 240, 255, 1)',
                        'rgba(234, 240, 255, 1)',
                        'rgba(234, 240, 255, 1)',
                        'rgba(234, 240, 255, 1)',
                        'rgba(234, 240, 255, 1)'];


        // Crear el gráfico
        var graficoVentas = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Ventas',
                    data: ventas,
                    backgroundColor: colores,
                    borderColor: bordes,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<?php include __DIR__.'/include/pie.php';?>