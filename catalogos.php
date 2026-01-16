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

$tituloPagina = "Flor de Gimeno | Catálogos";
$menu = "catalogos";

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

$temporada = $_GET['temporada'] ?? '';
$año = $_GET['año'] ?? '';
$añosDisponibles = Catalogo::listadoAños();

if ($temporada !== '' || $año !== '') {
    $catalogos = Catalogo::listadoFiltrado($temporada, $año, $pag);
    $numCatalogos = Catalogo::cuentaFiltrado($temporada, $año);
} else {
    $catalogos = Catalogo::listado($pag);
    $numCatalogos = Catalogo::cuenta();
}

$numPaginas = ceil($numCatalogos / TAM_PAGINA2);


include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<form method="GET" action="catalogos.php" id="formFiltros" class="row g-2 mt-3 filtros">
    <!-- Desplegable para seleccionar una temporada y filtrar por dicha temporada -->
    <div class="col-md-3">
        <select name="temporada" class="form-select filtro-auto">
            <option value="">Todas las temporadas</option>
            <option value="Primavera-Verano" <?= ($temporada=='Primavera-Verano')?'selected':'' ?>>Primavera - Verano</option>
            <option value="Otoño-Invierno" <?= ($temporada=='Otoño-Invierno')?'selected':'' ?>>Otoño - Invierno</option>
        </select>
    </div>

    <!-- Desplegable para seleccionar un año determinado y filtrar por dicho año -->
    <div class="col-md-3">
        <select name="año" class="form-select filtro-auto">
            <option value="">Todos los años</option>
            <?php foreach ($añosDisponibles as $a): ?>
                <option value="<?= e($a) ?>" <?= ($año==$a)?'selected':'' ?>>
                    <?= e($a) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <input type="hidden" name="pag" value="1">

</form>


<!--Listado de catálogos-->
<?php if (!empty($catalogos)) : ?>
    <table class="table table-hover table-catalogos">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Temporada</th>
                <th>Año</th>
                <th style="text-align: center;">Fichero</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($catalogos as $catalogo) : ?>
                <tr id="fila<?= e($catalogo['idCatalogo'])?>">
                    <td><?= e($catalogo['nombre'])?></td>
                    <td><?= e($catalogo['temporada'])?></td>
                    <td><?= e($catalogo['año'])?></td>
                    <td style="text-align: center;">
                        <a href="ficheros/<?= e($catalogo['idCatalogo']) . '_' . e($catalogo['archivoPDF']) ?>" id="btnDescargar" class="btn btn-ok"  title="Descargar catálogo" download>
                            <i class="bi bi-download"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="row">
        <div class="col-md-12 text-center" style="margin-top: 50px;">
            <h5>No hay ningún catálogo disponible</h5>
        </div>
    </div>
<?php endif; ?>


<!--Paginación-->
<?php if (($numPaginas)>1) : ?>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pag == 1) echo 'disabled'; ?>">
                <a class="page-link" href="catalogos.php?pag=<?= $pag - 1 ?>&temporada=<?= e($temporada) ?>&año=<?= e($año) ?>">
                    &lt;&lt;
                </a>
            </li>
            <?php for ($i = 1; $i <= $numPaginas; $i++) : ?>
                <li class="page-item <?php if ($pag == $i) echo 'active'; ?>">
                    <a class="page-link" href="catalogos.php?pag=<?= $i ?>&temporada=<?= e($temporada) ?>&año=<?= e($año) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($pag == $numPaginas) ? "disabled" : "" ?>">
                <a class="page-link" href="catalogos.php?pag=<?= $pag + 1 ?>&temporada=<?= e($temporada) ?>&año=<?= e($año) ?>">
                    &gt;&gt;
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    document.querySelectorAll('.filtro-auto').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('formFiltros').submit();
        });
    });
</script>

<?php include __DIR__.'/include/pie.php';?>