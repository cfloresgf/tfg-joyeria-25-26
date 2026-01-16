<?php
require_once __DIR__.'/modelos/usuario.php';
session_start();

$login = $_POST['login'];
$pwd = $_POST['pwd'];

$usuario = Usuario::cargaLogin($login);
if ($usuario) {
    if (password_verify($pwd, $usuario->pwd)) {
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        die();
    }
}

$_SESSION['error-login'] = "Nombre de usuario o contraseña incorrectos";
header('Location: login.php');
