<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/tipoProducto.php';
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

//Verificación de errores y ver si es un nuevo producto o es uno ya existente
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $producto = $_SESSION['datos'];
    $id = $producto->idProducto;
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
}
else if (isset($_GET['id'])) {
    $errores = [];
    $id = $_GET['id'];
    $producto = Producto::cargar($id);
    $pag = $_GET['pag'];
}
else {
    $errores = [];
    $id = 0;
    $producto = new Producto();
}


if ($id == 0) {
    $tituloPagina = "Flor de Gimeno | Nuevo Producto";
    $titulo = "Nuevo Producto";
}
else {
    $tituloPagina = "Flor de Gimeno | Edición de Producto";
    $titulo = "Edición de Producto";
}

$tipos = TiposProducto::listado();


include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<div class="row" style="justify-content: center;">

    <h3 id="tituloFormularios"><?=$titulo?></h3>

    <form action="guardarProducto.php?id=<?= e($producto->idProducto)?><?php if (isset($_GET['id'])):?>&pag=<?=$pag?><?php endif;?>"
        method="POST" class="row g-3" style="margin-top: -5px; margin-bottom: 20px; width: 80%;" enctype="multipart/form-data">

        <input type="hidden" value="<?= e($producto->idProducto)?>" name="idProducto"/>

        <!--Nombre-->
        <div class="col-md-12">
            <label class="form-label" for="nombre">
                Nombre
            </label>
            <input type="text" id="nombre" name="nombre" class="form-control
                <?php if (isset($errores['nombre'])): echo 'is-invalid'; endif;?>"
                placeholder="Nombre:" value="<?= e($producto->nombre)?>"/>
            <?php if (isset($errores['nombre'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['nombre']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Descripción-->
        <div class="col-md-12">
            <label class="form-label" for="descripcion">
                Descripción
            </label>
            <textarea type="text" rows="5" id="descripcion" name="descripcion" class="form-control
                <?php if (isset($errores['descripcion'])): echo 'is-invalid'; endif;?>"
                placeholder="Descripcion:"><?= e($producto->descripcion)?></textarea>
            <?php if (isset($errores['descripcion'])): ?>
            <div class="invalid-feedback">
                <?= e($errores['descripcion']) ?>
            </div>
            <?php endif; ?>
        </div>

        <!--Imagen-->
        <div class="col-md-6">
            <input type="file" id="imagen" name="imagen" class="d-none
                <?php if (isset($errores['imagen'])): echo 'is-invalid'; endif;?>"/>
            <p>Imagen</p>
            <?php if($id == 0) : ?>
                <img id="foto" src="fotos/gris.jpg" style="max-height: 200px" />
            <?php else: ?>
                <img id="foto" src="fotos/<?= e($producto->idProducto) . '_' . e($producto->imagen)?>" style="max-height: 200px" />
            <?php endif; ?>
            <br/>
            <?php if (isset($errores['imagen'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['imagen']) ?>
                </div>
            <?php endif; ?>
            <button type="button" id="btnSelect" class="btn btn-select-foto btn-ok">
                Seleccionar foto
            </button>
        </div>

        <div class="col-md-6" style="align-content: center;">
            <!--Tipo producto-->
            <div style="margin-bottom: 40px;">
                <label class="form-label" for="tipo">
                    Tipo de producto
                </label>
                <select id="tipo" name="tipo" class="form-select">
                    <?php foreach ($tipos as $tipo): ?> 
                        <option value="<?= $tipo->idTipo?>"
                            <?php if($tipo->idTipo == $producto->idTipo) : ?>
                                selected
                            <?php endif;?>>

                            <?= e($tipo->nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!--Precio-->
            <div>
                <label class="form-label" for="precio">
                    Precio (€)
                </label>
                <input type="number" id="precio" name="precio" class="form-control
                    <?php if (isset($errores['precio'])): echo 'is-invalid'; endif;?>"
                    placeholder="Precio:" value="<?= e($producto->precio)?>"
                    step="0.01" min="0"/>
                <?php if (isset($errores['precio'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errores['precio']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!--Botones guardar y cancelar-->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-ok" id="btnGuardarProducto">
                Guardar
            </button>
            <a href="listadoProductos.php<?php if(isset($_GET['id'])):?>?pag=<?=$pag?><?php endif;?>" class="btn btn-cancelar">
                Cancelar
            </a>
        </div>
        
    </form>
</div>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    //Foto -----------------------------------------------------------------------------------------------------------

    const imagen = document.getElementById("imagen");
    const foto = document.getElementById("foto");
    const btnSelect = document.getElementById("btnSelect");

    imagen.addEventListener("change", leerImagen);
    btnSelect.addEventListener("click", function() {
        imagen.click();
    });

    foto.addEventListener("dragenter", function() {
        foto.classList.add("border", "border-3", "border-primary");
    });

    foto.addEventListener("dragleave", function() {
        foto.classList.remove("border", "border-3", "border-primary");
    });

    foto.addEventListener("dragover", function(e) {
        foto.classList.add("border", "border-3", "border-primary");
        e.preventDefault();
    });

    foto.addEventListener("drop", function(e) {
        if (e.dataTransfer.files) {
            imagen.files = e.dataTransfer.files;
            leerImagen();
        }
        foto.classList.remove("border", "border-3", "border-primary");
        e.preventDefault();
    });

    function leerImagen() {
        let f = imagen.files[0];
        let reader = new FileReader();
        reader.onloadend = function() {
            let data = reader.result;
            foto.src = data;
        };
        reader.readAsDataURL(f);
    }
   
</script>

<?php include __DIR__.'/include/pie.php';?>
