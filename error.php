<?php

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

if($usuario->admin==0) {
    http_response_code(403);
    die("Error: A esta página sólo pueden acceder los administradores");
}
