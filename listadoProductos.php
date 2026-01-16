<?php
require_once __DIR__.'/modelos/usuario.php';
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

$tituloPagina = "Flor de Gimeno | Listado de Productos";
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

$numProductos = Producto::cuenta();
$productos = Producto::listadoAdministrativo($pag);

$numPaginas = ceil($numProductos / TAM_PAGINA2);

include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    td, th {
        align-content: center;
    }
</style>


<?php if (!empty($productos)) : ?>
    <div class="table-responsive">
        <table class="table table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="padding-left: 10px;">Imagen</th>
                    <th>Nombre del producto</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th class="text-center">
                        <a href="formProductos.php" type="button" class="btn btn-ok">
                            Nuevo producto
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) : ?>
                    <tr id="fila<?= e($producto['idProducto'])?>">
                        <td style="margin-left: 2px;"><img src="fotos/<?= e($producto['idProducto']) . '_' . e($producto['imagen'])?>" style="height: 120px; width:120px;"/></td>
                        <td><?= e($producto['nombre'])?></td>
                        <td><?= e($producto['tipo'])?></td>
                        <td><?= e($producto['precio'])?> €</td>
                        <td class="text-center">
                            <a href="formProductos.php?id=<?= e($producto['idProducto'])?>&pag=<?=$pag?>" type="button" class="btn btnEditar">
                                Editar
                            </a>
                            <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#modal<?= e($producto['idProducto'])?>">
                                Borrar
                            </button>
                        </td>

                        <div class="modal fade" id="modal<?= e($producto['idProducto'])?>" tabindex="-1" aria-labelledby="modalLabel<?= e($producto['idProducto'])?>" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <?php if(Producto::verificaEliminacion($producto['idProducto'])===0):?>
                                        <div class="modal-header justify-content-center align-items-center">
                                            <h5 class="modal-title" id="modalLabel<?= e($producto['idProducto'])?>">Confirmación</h5>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>¿Estás seguro de eliminar el producto <strong><?= e($producto['nombre'])?></strong>?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-danger btnBorrarProducto" id="idProducto<?=$producto['idProducto']?>" data-bs-dismiss="modal">
                                                Borrar
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    <?php else:?>
                                        <div class="modal-header justify-content-center align-items-center">
                                            <h5 class="modal-title" id="modalLabel<?= e($producto['idProducto'])?>">Error</h5>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>El producto <strong><?= e($producto['nombre'])?></strong> no puede ser eliminado porque ya hay un pedido que lo contiene.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="width: 15%;">
                                                Salir
                                            </button>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-md-12 text-center" style="margin-top: 50px;">
            <h5>No hay ningún producto</h5>
        </div>
    </div>
<?php endif; ?>


<!--Paginación-->
<?php if (($numPaginas)>1) : ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                <a class="page-link" href="listadoProductos.php?pag=<?= $pag - 1 ?>">
                    &lt;&lt;
                </a>
            </li>
            <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                    <a class="page-link" href="listadoProductos.php?pag=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                <a class="page-link" href="listadoProductos.php?pag=<?= $pag + 1 ?>">
                    &gt;&gt;
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<?php include __DIR__.'/include/pie.php';?>
