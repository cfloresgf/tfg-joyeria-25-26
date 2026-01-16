<?php
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

$idUsuario = $_POST['idUsuario'];
Usuario::eliminar($idUsuario);
