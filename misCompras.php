<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/pedido.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Mis Compras";
$menu = "misCompras";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

if (isset($_GET['pag'])) {
    $pag = $_GET['pag'];
} else {
    $pag = 1;
}

$pedidos = Pedido::listadoSimpleFiltradoUsuario($usuario->idUsuario, $pag);

$numPedidos = Pedido::cuentaSimpleFiltradoUsuario($usuario->idUsuario);
$numPaginas = ceil($numPedidos / TAM_PAGINA2);

include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<div class="container mis-compras">
    <div class="row align-items-center justify-content-between">
        <h5 class="col-6">HISTORIAL DE COMPRAS</h5>
    </div>
</div>

<?php if (!empty($pedidos)) : ?>
    <div class="mis-compras-pedido">
        <?php foreach($pedidos as $pedido) : ?>
            <a class="pedidoGeneral btnDetalles" data-pedido-id="<?=$pedido['idPedido']?>">
                <div>
                    <p class="pedidoGeneralSecciones">
                        <span id="pedidoTitulos">Fecha de Compra: </span>
                        <?=formatearFechaHoraLarga (e($pedido['fechaPedido']))?>
                    </p>
                    <p class="pedidoGeneralSecciones">
                        <span id="pedidoTitulos">Número de Artículos: </span>
                        <?= $numProductos = Pedido::cuentaProductos($pedido['idPedido']) ?>
                    </p>
                    <p class="pedidoGeneralSecciones">
                        <span id="pedidoTitulos">Total de la Compra: </span>
                        <?=e($pedido['importeTotal'])?> €
                    </p>
                </div>
                <div>
                    <?php if($pedido['estado']==0) : ?>
                        <p class="pedidoGeneralSecciones estado estado-redondo estado-0">
                            Pendiente
                        </p>
                    <?php elseif($pedido['estado']==1): ?>
                        <p class="pedidoGeneralSecciones estado estado-redondo estado-1">
                            Finalizado
                        </p>
                    <?php elseif($pedido['estado']==2): ?>
                        <p class="pedidoGeneralSecciones estado estado-redondo estado-2">
                            Cancelado
                        </p>
                    <?php else: ?>
                        <p class="pedidoGeneralSecciones estado estado-redondo estado-3">
                            Devuelto
                        </p>
                    <?php endif;?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!--Paginación-->
    <?php if (($numPaginas)>1) : ?>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="misCompras.php?pag=<?= $pag - 1 ?>">
                        &lt;&lt;
                    </a>
                </li>
                <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                    <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                        <a class="page-link" href="misCompras.php?pag=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                    <a class="page-link" href="misCompras.php?pag=<?= $pag + 1 ?>">
                        &gt;&gt;
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

<?php else : ?>
    <div class="row" style="padding: 40px 0;">
        <div class="col-md-12 text-center" style="margin-top: 50px;">
            <h5>Todavía no has realizado ninguna compra</h5>
            <p>¡Ve a la tienda y encuentra algo que te guste!</p>
            <a class="btn btn-ok" href="productos.php">Ir a la tienda</a>
        </div>
    </div>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    //Al pulsar en un pedido, te lleva a sus detalles
    let pag = <?=$pag?>;
    const btnsDetalles = document.querySelectorAll(".btnDetalles");
    btnsDetalles.forEach(function(btn) {
        btn.addEventListener("click", function(b) {
            let idPedido = this.getAttribute("data-pedido-id");
            window.location.href = "detallesPedido.php?idPedido=" + idPedido + "&pag=" + pag;
        });
    });
</script>

<?php include __DIR__.'/include/pie.php';?>
