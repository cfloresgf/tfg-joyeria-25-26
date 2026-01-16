<?php 
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/producto.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}


if(isset($_POST['idLinea'])) {
    
    $linea = LineaCarrito::cargar($_POST['idLinea']);
    $carrito = Carrito::cargar($linea->idCarrito);
    $producto = Producto::cargar($linea->idProducto);

    $linea->cantidad --;

    if ($linea->cantidad <= 0) {
        // Eliminar la línea del carrito
        LineaCarrito::eliminar($linea->idLinea);
        $cantidadRestada = $producto->precio * $linea->cantidad;
    } else {
        // Actualizar la cantidad en la base de datos
        $linea->actualizar();
        $cantidadRestada = $producto->precio;
    }

    //Eliminar precio del producto del importeTotal del carrito
    $carrito->importeTotal -= $producto->precio;
    if($carrito->importeTotal>100) {
        $carrito->envioGratuito = 1;
    }
    else {
        $carrito->envioGratuito = 0;
    }
    $carrito->actualizarImporte();
}

header("Content-Type: application/json");
echo json_encode($linea);