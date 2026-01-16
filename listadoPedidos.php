<?php
require_once __DIR__.'/modelos/usuario.php';
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

$tituloPagina = "Flor de Gimeno | Listado de Pedidos";
$menu = "administracion";

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

$numPedidos = Pedido::cuentaAdministrativo();
$pedidos = Pedido::listadoAdministrativo($pag);

$numPaginas = ceil($numPedidos / TAM_PAGINA2);

include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    td, th {
        align-content: center;
    }
</style>

<?php if (!empty($pedidos)) : ?>
    <div class="table-responsive">
        <table class="table table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%; padding-left: 10px;">Id</th>
                    <th style="width: 17%;">Fecha Pedido</th>
                    <th style="width: 20%;">Cliente</th>
                    <th style="width: 17%; padding-left: 10px;">Estado</th>
                    <th style="width: 17%; padding-left: 10px;">Fecha Envío</th>
                    <th style="width: 17%;">Anulación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido) : ?>
                    <tr id="fila<?= e($pedido['idPedido'])?>">
                        <td style="padding-left: 10px;"><?=e($pedido['idPedido'])?></td>
                        <td><?= formatearFechaHoraLarga (e($pedido['fechaPedido']))?></td>
                        <td><?= e($pedido['cliente'])?></td>
                        <td id="estado<?= e($pedido['idPedido'])?>">
                            <?php if($pedido['estado']==0) : ?>
                                <p class="estado estado-0" id="pPendiente<?=$pedido['idPedido']?>">
                                    Pendiente
                                </p>
                            <?php elseif($pedido['estado']==1): ?>
                                <p class="estado estado-1" id="pFinalizado<?=$pedido['idPedido']?>">
                                    Finalizado
                                </p>
                            <?php elseif($pedido['estado']==2): ?>
                                <p class="estado estado-2">
                                    Cancelado
                                </p>
                            <?php else: ?>
                                <p class="estado estado-3">
                                    Devuelto
                                </p>
                            <?php endif;?>
                        </td>
                        <td id="envio<?= e($pedido['idPedido'])?>" style="margin-left: 10px;">
                            <?php if($pedido['estado']==0) : ?>
                                <button type="button" class="btn btn-outline-success btnEnviar" id="btnEnviar<?=$pedido['idPedido']?>" style="margin-left: 5px; width: 105px;">
                                    Enviar
                                </button>
                            <?php elseif($pedido['estado']==1 || $pedido['estado']==3): ?>
                                <?= formatearFechaHoraLarga (e($pedido['fechaEnvio']))?>
                            <?php else: ?>
                                <span style="color: #FF7F7F;">Envío cancelado</span>
                            <?php endif;?>
                        </td>
                        <td id="anulacion<?= e($pedido['idPedido'])?>">
                            <?php if($pedido['estado']==0) : ?>
                                <button type="button" class="btn btn-outline-danger" style="width: 105px;" id="btnCancelar<?=$pedido['idPedido']?>"
                                data-bs-toggle="modal" data-bs-target="#modalCancelacion<?= e($pedido['idPedido'])?>">
                                    Cancelar
                                </button>
                            <?php elseif($pedido['estado']==1): ?>
                                <button type="button" class="btn btn-outline-primary" style="width: 105px;" id="btnDevolver<?=$pedido['idPedido']?>"
                                data-bs-toggle="modal" data-bs-target="#modalDevolucion<?= e($pedido['idPedido'])?>">
                                    Devolución
                                </button>
                            <?php elseif($pedido['estado']==2 || $pedido['estado']==3): ?>
                                <?= formatearFechaHoraLarga (e($pedido['fechaAnulacion']))?>
                            <?php endif;?>
                        </td>

                        <div class="modal fade" id="modalCancelacion<?= e($pedido['idPedido'])?>" tabindex="-1" aria-labelledby="modalLabel<?= e($pedido['idPedido'])?>" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center align-items-center">
                                        <h5 class="modal-title" id="modalLabel<?= e($pedido['idPedido'])?>">Confirmación</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>¿Estás seguro de cancelar el pedido <strong><?= e($pedido['idPedido'])?></strong>?</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger btnCancelar" id="idPedido<?=$pedido['idPedido']?>" data-bs-dismiss="modal">
                                            Aceptar
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="modal fade" id="modalDevolucion<?= e($pedido['idPedido'])?>" tabindex="-1" aria-labelledby="modalLabel<?= e($pedido['idPedido'])?>" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center align-items-center">
                                        <h5 class="modal-title" id="modalLabel<?= e($pedido['idPedido'])?>">Confirmación</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>¿Estás seguro de devolver el pedido <strong><?= e($pedido['idPedido'])?></strong>?</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-primary btnDevolver" id="idPedido<?=$pedido['idPedido']?>" data-bs-dismiss="modal">
                                            Aceptar
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (($numPaginas)>1) : ?>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="listadoPedidos.php?pag=<?= $pag - 1 ?>">
                        &lt;&lt;
                    </a>
                </li>
                <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                    <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                        <a class="page-link" href="listadoPedidos.php?pag=<?= $i ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                    <a class="page-link" href="listadoPedidos.php?pag=<?= $pag + 1 ?>">
                        &gt;&gt;
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif;?>
    
<?php else : ?>
    <div class=" text-center" style="margin-top: 50px; margin-bottom: 50px;">
        <h5>No hay ningún pedido</h5>
    </div>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<?php include __DIR__.'/include/pie.php';?>



<!--
    Los pedidos pueden tener 4 estados: 
        0 - Pendiente es cuando se acaba de realizar la compra
        1 - Finalizado es cuando se ha enviado el pedido
        2 - Cancelado es cuando estando en modo pendiente se ha cancelado el pedido
        3 - Devuelto es cuando estando en modo finalizado se ha devuelto un pedido.
-->