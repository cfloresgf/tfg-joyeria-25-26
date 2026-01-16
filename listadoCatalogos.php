<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/catalogo.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Listado de Catálogos";
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

$numCatalogos = Catalogo::cuentaAdministrativo();
$catalogos = Catalogo::listadoAdministrativo($pag);

$numPaginas = ceil($numCatalogos / TAM_PAGINA2);

include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    td, th {
        align-content: center;
    }
</style>

<?php if (!empty($catalogos)) : ?>
    <div class="table-responsive">
        <table class="table table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="padding-left: 10px;">Nombre</th>
                    <th>Temporada</th>
                    <th>Año</th>
                    <th>Fichero</th>
                    <th class="text-center">Activo</th>
                    <th class="text-center">
                        <a href="formCatalogos.php" type="button" class="btn btn-ok">
                            Nuevo catálogo
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catalogos as $catalogo) : ?>
                    <tr id="fila<?= e($catalogo['idCatalogo'])?>">
                        <td style="padding-left: 10px;"><?= e($catalogo['nombre'])?></td>
                        <td><?= e($catalogo['temporada'])?></td>
                        <td><?= e($catalogo['año'])?></td>
                        <td>
                            <?php if (!empty($catalogo['archivoPDF'])): ?>
                                <?= e($catalogo['archivoPDF']) ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= $catalogo['activo'] == 1 ? 'Sí' : 'No' ?></td>
                        <td class="text-center">
                            <a href="formCatalogos.php?id=<?= e($catalogo['idCatalogo'])?>&pag=<?=$pag?>" type="button" class="btn btnEditar">
                                Editar
                            </a>
                            <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#modal<?= e($catalogo['idCatalogo'])?>">
                                Borrar
                            </button>
                        </td>

                        <div class="modal fade" id="modal<?= e($catalogo['idCatalogo'])?>" tabindex="-1" aria-labelledby="modalLabel<?= e($catalogo['idCatalogo'])?>" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center align-items-center">
                                        <h5 class="modal-title" id="modalLabel<?= e($catalogo['idCatalogo'])?>">Confirmación</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>¿Estás seguro de eliminar el catálogo <strong><?= e($catalogo['nombre'])?></strong>?</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger btnBorrarCatalogo" id="idCatalogo<?=$catalogo['idCatalogo']?>" data-bs-dismiss="modal">
                                            Borrar
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
<?php else : ?>
    <div class="row">
        <div class="col-md-12 text-center" style="margin-top: 50px;">
            <h5>No hay ningún catálogo</h5>
        </div>
    </div>
<?php endif; ?>


<!--Paginación-->
<?php if (($numPaginas)>1) : ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                <a class="page-link" href="listadoCatalogos.php?pag=<?= $pag - 1 ?>">
                    &lt;&lt;
                </a>
            </li>
            <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                    <a class="page-link" href="listadoCatalogos.php?pag=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                <a class="page-link" href="listadoCatalogos.php?pag=<?= $pag + 1 ?>">
                    &gt;&gt;
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<?php include __DIR__.'/include/pie.php';?>
