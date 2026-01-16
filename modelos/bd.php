<?php
const TAM_PAGINA = 12;
const TAM_PAGINA2 = 5;

function abrirBD() {
    $bd = new mysqli(
            "localhost",   // Servidor
            "usuario",     // Usuario
            "usuario",     // Contraseña
            "joyeriaBD");  // Esquema
    if ($bd->connect_errno) {
        die("Error de conexión: " . $bd->connect_error);
    }
    $bd->set_charset("utf8mb4");
    return $bd;
}
