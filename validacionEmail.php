<?php
require_once __DIR__.'/modelos/usuario.php';

$email = $_POST['email'];
$id = $_POST['idUsuario'];

$usuario = Usuario::cargaLogin($email);
if ($usuario && $usuario->idUsuario != $id) {
    echo "DUPLICADO";
}
else {
    echo "OK";
}
