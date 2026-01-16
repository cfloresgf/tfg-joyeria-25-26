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


if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

include __DIR__.'/error.php';


$id = $_POST['idUsuario'];
if ($id == null) {
    $u = new Usuario();
}
else {
    $u = Usuario::cargar($id);
    $pag = $_GET['pag'];
}

$u->nombre = $_POST['nombre'];
$u->email = $_POST['email'];
if ($_POST['pwd'] != "") {
    $u->pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
}
$u->telefono = $_POST['telefono'];
$u->admin = isset($_POST['admin']);


$errores = [];
if ($u->nombre == '') {
    $errores['nombre'] = "Nombre requerido";
}
if ($u->email == '') {
    $errores['email'] = "Email requerido";
}
if ($u->telefono == '') {
    $errores['telefono'] = "Teléfono requerido";
}
if ($u->pwd == '') {
    $errores['pwd'] = "Contraseña requerida";
}


if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $u;
    header("Location: formUsuarios.php");
}
else {
    $u->guardar();
    if($pag != null) {
        header("Location: listadoUsuarios.php?pag=".$pag);
    }
    else {
        header("Location: listadoUsuarios.php");
    }
}
