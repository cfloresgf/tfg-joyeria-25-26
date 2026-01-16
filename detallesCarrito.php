<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
require_once __DIR__.'/modelos/carrito.php';
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


$tituloPagina = "Flor de Gimeno | Mi Carrito";
$menu = "miCarrito";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}


$lineasCarrito = Carrito::listado($usuario->idUsuario);


include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
        margin-top: 80px;
    }
</style>

<div class="container" style="margin-bottom: 50px;">
    <?php if (!empty($lineasCarrito)) : ?>
        <div class="row">
            <div class="col-md-8">
                <h5 class="titulo-carrito">MI CARRITO</h5>
                <?php foreach($lineasCarrito as $linea) : ?>
                    <div class="carritoItem">
                        <div class="productoInfo row">
                            <div class="col-3">
                                <img src="fotos/<?=e($linea['idProducto']) . '_' . e($linea['imagen'])?>" class="productoImg">
                            </div>
                            <div class="col-6">
                                <div class="productoNombre"><?=e($linea['nombre'])?></div>
                                <div class="productoPrecio">Precio: <?=e($linea['precio'])?> €</div>
                                <div class="botones">
                                    <button id="btnMasYMenos" class="btn btn-sm mr-1 btnMenos" data-id="<?= e($linea['idLinea']) ?>">-</button>
                                    <span><?=e($linea['cantidad'])?></span>
                                    <button id="btnMasYMenos" class="btn btn-sm ml-1 btnMas" data-id="<?= e($linea['idLinea']) ?>">+</button>
                                </div>
                            </div>
                            <div class="col-3 text-right subtotal">Subtotal: <?=number_format(e($linea['cantidad']) * e($linea['precio']), 2)?> €</div>
                        </div>  
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="carritoPagar">
                    <!-- Total del carrito -->
                    <h5>RESUMEN DE TU PEDIDO</h5>
                    <div class="row">
                        <div class="col-6">Subtotal</div>
                        <div class="col-6 text-right"><?=e($linea['importeTotal'])?> €</div>
                    </div>
                    <div class="row">
                        <div class="col-6">Envío</div>
                        <div class="col-6 text-right">2,95 €</div>
                    </div>
                    <?php if(e($linea['envioGratuito'])==1): ?>
                        <div class="row verde">
                            <div class="col-6">Descuento en el envío</div>
                            <div class="col-6 text-right">- 2,95 €</div>
                        </div>
                    <?php endif;?>
                    <div class="row">
                        <div class="col-6">Total</div>
                        <?php if(e($linea['envioGratuito'])==1): ?>
                            <div class="col-6 text-right"><?=e($linea['importeTotal'])?> €</div>
                        <?php else: ?>
                            <div class="col-6 text-right"><?=number_format(e($linea['importeTotal']) + '2.95', 2)?> €</div>
                        <?php endif;?> 
                    </div>
                    <div class="info">Impuestos incluidos</div>
                    <button id="btnPagar" class="checkout-button btn btn-ok">PAGAR</button>
                </div>
            </div>
        </div>
        
        <?php if(e($linea['envioGratuito'])==1): ?>
            <div class="envio-gratuito"><i class="bi bi-truck"></i> Este pedido cumple los requisitos para envío estándar GRATUITO</div>
        <?php else: ?>
            <div class="envio-gratuito"><i class="bi bi-truck"></i> ENVÍO GRATUITO con compras superiores a 100€</div>
        <?php endif;?> 

    <?php else : ?>
        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 50px;">
                <h5>No tienes ningún producto en tu carrito</h5>
                <p>¡Ve a la tienda y encuentra algo que te guste!</p>
                <a class="btn btn-ok" href="productos.php">Ir a la tienda</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<div id="modalCompraRealizada" class="modalCompra">
    <div class="modalContenidoCompra">
        <div class="divCerrarModalCompra">
            <span class="btnCerrarModalCompra">&times;</span>
        </div>
        <p>¡Compra realizada con éxito!</p>
        <a href="#" class="btn btn-cancelar">Ver pedido</a>
    </div>
</div>


<?php include __DIR__.'/include/scripts.php';?>

<script>

    //Disminuir cantidad carrito
    const btnsMenos = document.querySelectorAll(".btnMenos");
    btnsMenos.forEach(function(btn) {
        btn.addEventListener("click", function(b) {
            const idLinea = this.getAttribute("data-id");
            let datos = new FormData();
            datos.append('idLinea', idLinea);

            fetch("disminuirCantidadCarrito.php", {
                method: 'POST',
                body: datos
            })
            .catch(error => console.error('Error:', error));

            location.reload();
        });
    });

    //Aumentar cantidad carrito
    const btnsMas = document.querySelectorAll(".btnMas");
    btnsMas.forEach(function(btn) {
        btn.addEventListener("click", function(b) {
            const idLinea = this.getAttribute("data-id");
            let datos = new FormData();
            datos.append('idLinea', idLinea);

            fetch("aumentarCantidadCarrito.php", {
                method: 'POST',
                body: datos
            })
            .catch(error => console.error('Error:', error));

            location.reload();
        });
    });


    // Obtener el modal y el botón
    var modal = document.getElementById("modalCompraRealizada");
    var btnPagar = document.getElementById("btnPagar");
    var span = document.getElementsByClassName("btnCerrarModalCompra")[0];

    // Cuando se haga clic en el botón de pagar, poner el carrito en no activo, insertar los datos del carrito en un pedido y mostrar el modal
    if (btnPagar != null) {
        btnPagar.addEventListener("click", function () {
            fetch("realizarPagoCompra.php")
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        // Insertar el idPedido en el enlace
                        const enlace = document.querySelector(".btn-cancelar");
                        enlace.href = "detallesPedido.php?idPedido=" + data.idPedido + "&pag=1";
                        modal.style.display = "block";
                    } else {
                        alert("Error al realizar el pedido");
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    }

    // Cuando se haga clic en el botón de cerrar, ocultar el modal
    span.addEventListener("click", function(b) {
        modal.style.display = "none";
        location.reload();
    });

    // Cuando el usuario haga clic fuera del modal, cerrarlo
    window.addEventListener("click", function(b) {
        if (event.target == modal) {
            modal.style.display = "none";
            location.reload();
        }
    });

</script>

<?php include __DIR__.'/include/pie.php';?>
