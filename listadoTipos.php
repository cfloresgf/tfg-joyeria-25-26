<?php
require_once __DIR__.'/modelos/tipoProducto.php';

$tipos = TiposProducto::listado();
header("Content-Type: application/json");
echo json_encode($tipos);