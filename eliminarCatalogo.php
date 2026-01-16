<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/catalogo.php';

session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

include __DIR__.'/error.php';

$idCatalogo = $_POST['idCatalogo'];
Catalogo::eliminar($idCatalogo);
