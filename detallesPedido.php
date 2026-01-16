<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/pedido.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Detalles Pedido";
$menu = "misCompras";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

$pag = $_GET['pag'];

if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];
}

$pedido = Pedido::cargar($idPedido);
$lineasPedido = Pedido::listadoGeneralDetalles($idPedido);
$carrito = Carrito::cargar($pedido->idCarrito);

include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #F1F4FD;
        padding: 0;
    }
</style>

<div class="container detalles-pedido">
    <h1 class="detalles-pedido-titulo">Detalles del Pedido</h1>
    <div class="detallesPedidoSeccion">
        <span class="detallesPedidoTitulos">Fecha del Pedido:</span>
        <span><?=formatearFechaHoraLarga(e($pedido->fechaPedido))?></span>
    </div>
    <div class="detallesPedidoSeccion">
        <span class="detallesPedidoTitulos">Estado del Pedido:</span>
        <?php if($pedido->estado==0) : ?>
            <span class="estado-0-color">Pendiente</span>
        <?php elseif($pedido->estado==1):?>
            <span class="estado-1-color">Finalizado</span>
        <?php elseif($pedido->estado==2):?>
            <span class="estado-2-color">Cancelado</span>
        <?php else:?>
            <span class="estado-3-color">Devuelto</span>
        <?php endif;?>
    </div>
    <div class="detallesPedidoSeccion">
        <div class="lineasDetallesPedido">
            <?php foreach($lineasPedido as $linea) : ?>
                <div class="lineaDetallesPedido row">
                    <div class="col-3">
                        <img src="fotos/<?=e($linea['idProducto']) . '_' . e($linea['imagen'])?>" class="productoImg">
                    </div>
                    <div class="col-9">
                        <p class="detallesPedidoTitulos"><?=e($linea['nombre'])?></p>
                        <p class="item-quantity">Cantidad: <?=e($linea['cantidad'])?></p>
                        <p class="item-price">Precio Unitario: <?=e($linea['precio'])?> €</p>
                        <p class="item-subtotal">Subtotal: <?=number_format(e($linea['cantidad']) * e($linea['precio']), 2)?> €</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="detallesPedidoSeccion">
        <span class="detallesPedidoTitulos">Gastos de envío:</span>
        <?php if($carrito->envioGratuito==1): ?>
            <span>0.00 €</span>
        <?php else: ?>
            <span>2.95 €</span>
        <?php endif;?>
    </div>
    <div class="detallesPedidoSeccion">
        <span class="detallesPedidoTitulos">Total:</span>
        <span><?=$carrito->importeTotal?> €</span>
    </div>
    <a href="generarPDF.php?idCarrito=<?= $carrito->idCarrito ?>" class="btn btn-ok btn-exportar-PDF">Exportar PDF</a>
</div>


<?php include __DIR__.'/include/scripts.php';?>
<?php include __DIR__.'/include/pie.php';?>
