<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$tituloPagina = "Flor de Gimeno | Listado de Usuarios";
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

$numUsuarios = Usuario::cuenta();
$usuarios = Usuario::listado($pag);

$numPaginas = ceil($numUsuarios / TAM_PAGINA2);

include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    td, th {
        align-content: center;
    }
</style>

<?php if (!empty($usuarios)) : ?>
    <div class="table-responsive">
        <table class="table table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="padding-left: 10px;">Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Teléfono</th>
                    <th class="text-center">Administrador</th>
                    <th class="text-center">
                        <a href="formUsuarios.php" type="button" class="btn btn-ok">
                            Nuevo usuario
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr id="fila<?= e($usuario['idUsuario'])?>">
                        <td style="padding-left: 10px;"><?= e($usuario['nombre'])?></td>
                        <td><?= e($usuario['email'])?></td>
                        <td><?= e($usuario['telefono'])?></td>
                        <td class="text-center">
                            <?php if($usuario['admin']==0) : ?>
                                <p class="m-0">No</p>
                            <?php else:?>
                                <p class="m-0">Sí</p>
                            <?php endif;?>
                        </td>
                        <td class="text-center">
                            <a href="formUsuarios.php?id=<?= e($usuario['idUsuario'])?>&pag=<?=$pag?>" type="button" class="btn btnEditar">
                                Editar
                            </a>
                            <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#modal<?= e($usuario['idUsuario'])?>">
                                Borrar
                            </button>
                        </td>

                        <div class="modal fade" id="modal<?= e($usuario['idUsuario'])?>" tabindex="-1" aria-labelledby="modalLabel<?= e($usuario['idUsuario'])?>" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-center align-items-center">
                                        <h5 class="modal-title" id="modalLabel<?= e($usuario['idUsuario'])?>">Confirmación</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>¿Estás seguro de eliminar el usuario <strong><?= e($usuario['nombre'])?></strong>?</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger btnBorrarUsuario" id="idUsuario<?=$usuario['idUsuario']?>" data-bs-dismiss="modal">
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
            <h5>No hay ningún usuario</h5>
        </div>
    </div>
<?php endif; ?>


<!--Paginación-->
<?php if (($numPaginas)>1) : ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                <a class="page-link" href="listadoUsuarios.php?pag=<?= $pag - 1 ?>">
                    &lt;&lt;
                </a>
            </li>
            <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                    <a class="page-link" href="listadoUsuarios.php?pag=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                <a class="page-link" href="listadoUsuarios.php?pag=<?= $pag + 1 ?>">
                    &gt;&gt;
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<?php include __DIR__.'/include/pie.php';?>
