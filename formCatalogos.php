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

$menu = "administracion";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

//Verificación de errores y ver si es un nuevo catálogo o es uno ya existente
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $catalogo = $_SESSION['datos'];
    $id = $catalogo->idCatalogo;
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
}
else if (isset($_GET['id'])) {
    $errores = [];
    $id = $_GET['id'];
    $catalogo = Catalogo::cargar($id);
    $pag = $_GET['pag'];
}
else {
    $errores = [];
    $id = 0;
    $catalogo = new Catalogo();
}


if ($id == 0) {
    $tituloPagina = "Flor de Gimeno | Nuevo Catálogo";
    $titulo = "Nuevo Catálogo";
}
else {
    $tituloPagina = "Flor de Gimeno | Edición de Catálogo";
    $titulo = "Edición de Catálogo";
}


include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<div class="row" style="justify-content: center;">

    <h3 id="tituloFormularios"><?=$titulo?></h3>

    <form action="guardarCatalogo.php?id=<?= e($catalogo->idCatalogo)?><?php if (isset($_GET['id'])):?>&pag=<?=$pag?><?php endif;?>"
        method="POST" class="row g-3" style="margin-top: -5px; margin-bottom: 20px; width: 80%;" enctype="multipart/form-data">

        <input type="hidden" value="<?= e($catalogo->idCatalogo)?>" name="idCatalogo"/>

        <!--Nombre-->
        <div class="col-md-12">
            <label class="form-label" for="nombre">
                Nombre
            </label>
            <input type="text" id="nombre" name="nombre" class="form-control
                <?php if (isset($errores['nombre'])): echo 'is-invalid'; endif;?>"
                placeholder="Nombre:" value="<?= e($catalogo->nombre)?>"/>
            <?php if (isset($errores['nombre'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['nombre']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Temporada-->
        <div class="col-md-6">
            <label class="form-label" for="temporada">
                Temporada
            </label>
            <select id="temporada" name="temporada" class="form-select">
                <option value="Primavera-Verano" <?= ($catalogo->temporada=='Primavera-Verano')?'selected':'' ?>>Primavera - Verano</option>
                <option value="Otoño-Invierno" <?= ($catalogo->temporada=='Otoño-Invierno')?'selected':'' ?>>Otoño - Invierno</option>
            </select>
        </div>

        <!--Año-->
        <div class="col-md-6">
            <label class="form-label" for="año">
                Año
            </label>
            <input type="number" id="año" name="año" class="form-control
                <?php if (isset($errores['año'])): echo 'is-invalid'; endif;?>"
                placeholder="Año:" value="<?= e($catalogo->año ?? date('Y')) ?>"
                step="1" min="2000"/>
            <?php if (isset($errores['año'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['año']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Activo-->
        <div class="col-md-6">
            <label class="form-label" for="activo">
                Activo
            </label>
            <div class="form-check">
                <input type="checkbox" id="activo" name="activo" class="form-check-input"
                    value="1" <?php if (!isset($catalogo->activo) || $catalogo->activo == 1) echo 'checked'; ?> />
                <label class="form-check-label" for="activo">
                    Activo
                </label>
            </div>
        </div>

        <!--Archivo PDF-->
        <div class="col-md-6">
            <label class="form-label">Archivo PDF</label>
            <div id="archivoContainer">
                <?php if($id != 0 && !empty($catalogo->archivoPDF)) : ?>
                    <p id="nombreArchivo"><?= e($catalogo->archivoPDF) ?></p>
                    <a href="ficheros/<?= e($catalogo->idCatalogo) . '_' . e($catalogo->archivoPDF) ?>" id="btnDescargar" class="btn btn-outline-secondary" download>Descargar</a>
                <?php endif; ?>
                <button type="button" id="btnSelect" class="btn btn-ok">Seleccionar archivo</button>
            </div>
            <input type="file" id="archivoPDF" name="archivoPDF" class="d-none"/>
            <?php if (isset($errores['archivoPDF'])): ?>
                <div class="invalid-feedback d-block">
                    <?= e($errores['archivoPDF']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Botones guardar y cancelar-->
        <div class="col-12 text-center mt-5">
            <button type="submit" class="btn btn-ok" id="btnGuardarCatalogo">
                Guardar
            </button>
            <a href="listadoCatalogos.php<?php if(isset($_GET['id'])):?>?pag=<?=$pag?><?php endif;?>" class="btn btn-cancelar">
                Cancelar
            </a>
        </div>
        
    </form>
</div>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    //Fichero -----------------------------------------------------------------------------------------------------------
    const archivoPDF = document.getElementById("archivoPDF");
    const btnSelect = document.getElementById("btnSelect");
    const nombreArchivo = document.getElementById("nombreArchivo");
    const btnDescargar = document.getElementById("btnDescargar");

    btnSelect.addEventListener("click", function() {
        archivoPDF.click();
    });

    archivoPDF.addEventListener("change", function() {
        if(archivoPDF.files.length > 0) {
            // Mostrar el nombre del archivo seleccionado
            if(nombreArchivo) {
                nombreArchivo.textContent = archivoPDF.files[0].name;
            } else {
                // Crear el <p> si no existía (nuevo catálogo)
                const p = document.createElement("p");
                p.id = "nombreArchivo";
                p.textContent = archivoPDF.files[0].name;
                btnSelect.parentNode.insertBefore(p, btnSelect);
            }
            // Eliminar el botón de descargar si existía
            if(btnDescargar) {
                btnDescargar.remove();
            }
        }
    });
</script>

<?php include __DIR__.'/include/pie.php';?>
