<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
}

$producto = Producto::cargar($idProducto);

$tituloPagina = "Flor de Gimeno | " . e($producto->nombre);
$menu = "productos";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

$pag = $_GET['pag'];
$idTipo = $_GET['idTipo'];

include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    body {
      background-color: #F8F9FA;
      padding-top: 100px;
    }
</style>

<!--Características de un producto-->
<div class="container detalles-producto">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="producto">
                <h2 class="titulo"><?= e($producto->nombre)?></h2>
                <div class="row">
                    <div class="col-md-6 imagen">
                        <img src="fotos/<?= e($producto->idProducto) . '_' . e($producto->imagen)?>" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-6 textos">
                        <p class="descripcion">
                            <?= nl2br(e($producto->descripcion))?>
                        </p>
                        <p>Precio: <?= e($producto->precio)?> €</p>
                        <button id="botonAgregarCarrito" class="btn btn-ok btn-agregar-carrito">Añadir al carrito</button>
                        <input id="idProducto" value="<?= e($producto->idProducto)?>" hidden/>
                        <div id="popupCarrito" class="popup-carrito">
                            <p id="respuesta"></p>
                            <a id="linkCarrito" href="detallesCarrito.php">Ver carrito</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="btn" href="productos.php?pag=<?=$pag?><?php if($idTipo!=0):?>&idTipo=<?=$idTipo?><?php endif;?>">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>


<?php include __DIR__.'/include/scripts.php';?>

<script>

    //Añadir producto al carrito
    const boton = document.getElementById("botonAgregarCarrito");
    const idProducto = document.getElementById("idProducto").value;
    const respuesta = document.getElementById("respuesta");
    const popup = document.getElementById("popupCarrito");
    const linkCarrito = document.getElementById("linkCarrito");

    boton.addEventListener("click", function() {
        let datos = new FormData();
        datos.append('idProducto', idProducto);

        fetch("agregarProductoCarrito.php", {
            method: 'POST',
            body: datos
         })
        .then(response => response.json())
        .then(data => {
            respuesta.textContent = data.mensaje;
             if(data.ok) {
                linkCarrito.style.display = "inline";
            } else {
                linkCarrito.style.display = "none";
            }
            popup.style.display = "block";
            setTimeout(() => {
                popup.style.display = "none";
            }, 5000);
        })
        .catch(error => console.error('Error:', error));
    });

</script>

<?php include __DIR__.'/include/pie.php';?>
