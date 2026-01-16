<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$usuario->nombre = $_POST['nombre'];
if ($_POST['pwd'] != "") {
    $usuario->pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
}
$usuario->telefono = $_POST['telefono'];


$errores = [];
if ($usuario->nombre == '') {
    $errores['nombre'] = "Nombre requerido";
}
if ($usuario->telefono == '') {
    $errores['telefono'] = "Teléfono requerido";
}
if ($_POST['pwd'] !== '') {
    if (strlen($_POST['pwd']) < 6) {
        $errores['pwd'] = "Contraseña demasiado corta";
    } else {
        $usuario->pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
    }
}


if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $usuario;
    header("Location: formMiCuenta.php");
}
else {
    $usuario->guardar();
    header("Location: index.php");
}
