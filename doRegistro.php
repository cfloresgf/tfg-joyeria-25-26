<?php
require_once __DIR__.'/modelos/usuario.php';
session_start();

$u = new Usuario();
$u->nombre = $_POST['nombre'];
$u->email = $_POST['email'];
if ($_POST['pwd'] != "") {
    $u->pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
}
$u->telefono = $_POST['telefono'];
$u->admin = 0;

$errores = [];
if ($u->nombre == '') {
    $errores['nombre'] = "Nombre obligatorio";
}
if ($u->email == '') {
    $errores['email'] = "Email obligatorio";
}
if (!filter_var($u->email, FILTER_VALIDATE_EMAIL)) {
    $errores['email'] = "Email no válido";
}
if (Usuario::cargaLogin($email)) {
    $errores['email'] = "El email ya existe";
}
if ($u->telefono == '') {
    $errores['telefono'] = "Teléfono obligatorio";
}
if ($u->pwd == '') {
    $errores['pwd'] = "Contraseña obligatorio";
}
if (strlen($_POST['pwd']) < 6) {
    $errores['pwd'] = "Contraseña demasiado corta";
}


if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $u;
    header("Location: registro.php");
}
else {
    $u->guardar();
    $usuario = Usuario::cargaLogin($u->email);
    $_SESSION['usuario'] = $usuario;
    header('Location: index.php');
}
