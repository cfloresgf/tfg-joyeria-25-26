<?php
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/usuario.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

include __DIR__.'/error.php';


$idProducto = $_POST['idProducto'];

$producto=Producto::cargar($idProducto);
unlink($producto->getRutaFoto());
Producto::eliminar($idProducto);
