<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/pedido.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

include __DIR__.'/error.php';

$idPedido = $_POST['idPedido'];
$fecha = date("Y-m-d H:i:s");

Pedido::enviar($idPedido,$fecha);
