<?php 
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/pedido.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

    
//Cambia el estado del carrito a no activo
$carrito = Carrito::comprobarCarrito($usuario->idUsuario);
$carrito->activo = 0;
$carrito->actualizarEstado();

if($carrito->envioGratuito==0) {
    $carrito->importeTotal += 2.95;
}
$carrito->actualizarImporte();


//Crea el pedido con la información del carrito
$pedido = new Pedido();
$pedido->idCarrito = $carrito->idCarrito;
$pedido->fecha = date("Y-m-d H:i:s");
$pedido->estado = 0;

$pedido->insertar();


header("Content-Type: application/json");
echo json_encode([
    'ok' => true,
    'idPedido' => $pedido->idPedido
]);