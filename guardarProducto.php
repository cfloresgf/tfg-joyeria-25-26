<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
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


if ($_POST['idProducto'] == "") {
    $producto = new Producto();
}
else {
    $id = $_POST['idProducto'];
    $producto = Producto::cargar($id);
    $pag = $_GET['pag'];
}

$producto->nombre = $_POST['nombre'];
$producto->precio = $_POST['precio'];
$producto->descripcion = $_POST['descripcion'];
$producto->idTipo = $_POST['tipo'];

$imagen = $_FILES['imagen'];


$errores = [];
if ($producto->nombre == '') {
    $errores['nombre'] = "Nombre requerido";
}
if ($producto->precio == '') {
    $errores['precio'] = "Precio requerido";
}
if ($producto->descripcion == '') {
    $errores['descripcion'] = "Descripcion requerida";
}


// Validación de la imagen
if ($_POST['idProducto'] == "") {
    // Si es un nuevo producto, la imagen es obligatoria
    if ($imagen['error'] == UPLOAD_ERR_NO_FILE) {
        $errores['imagen'] = "Imagen requerida";
    }
}


if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $producto;
    header("Location: formProductos.php");
}
else {
    if ($_POST['idProducto'] != "") {
        //Si al editar no meten ninguna foto, que se quede la antigua
        if ($imagen['error'] != UPLOAD_ERR_NO_FILE) {
            //Elimina la foto antigua
            unlink($producto->getRutaFoto());
            $producto->imagen = $imagen['name'];
        }
    }
    else {
        $producto->imagen = $imagen['name'];
    }
    
    $producto->guardar();

    //Guarda la foto nueva
    move_uploaded_file($imagen['tmp_name'], $producto->getRutaFoto());

    if($pag != null) {
        header("Location: listadoProductos.php?pag=".$pag);
    }
    else {
        header("Location: listadoProductos.php");
    }
}
