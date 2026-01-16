<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/catalogo.php';
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


if ($_POST['idCatalogo'] == "") {
    $catalogo = new Catalogo();
}
else {
    $id = $_POST['idCatalogo'];
    $catalogo = Catalogo::cargar($id);
    $pag = $_GET['pag'];
}

$catalogo->nombre = $_POST['nombre'];
$catalogo->temporada = $_POST['temporada'];
$catalogo->año = $_POST['año'];
$catalogo->activo = isset($_POST['activo']) ? 1 : 0;

$archivoPDF = $_FILES['archivoPDF'];


$errores = [];
if ($catalogo->nombre == '') {
    $errores['nombre'] = "Nombre requerido";
}
if ($catalogo->año == '') {
    $errores['año'] = "Año requerido";
}


// Validación de la imagen
if ($_POST['idCatalogo'] == "") {
    // Si es un nuevo catálogo, el fichero es obligatorio
    if ($archivoPDF['error'] == UPLOAD_ERR_NO_FILE) {
        $errores['archivoPDF'] = "Archivo PDF requerido";
    }
}


if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $catalogo;
    header("Location: formCatalogos.php");
}
else {
    if ($_POST['idCatalogo'] != "") {
        //Si al editar no meten ningún fichero, que se quede el antiguo
        if ($archivoPDF['error'] != UPLOAD_ERR_NO_FILE) {
            //Elimina el fichero antiguo
            unlink($catalogo->getRutaFichero());
            $catalogo->archivoPDF = $archivoPDF['name'];
        }
    }
    else {
        $catalogo->archivoPDF = $archivoPDF['name'];
    }
    
    $catalogo->guardar();

    //Guarda el fichero nuevo
    move_uploaded_file($archivoPDF['tmp_name'], $catalogo->getRutaFichero());

    if($pag != null) {
        header("Location: listadoCatalogos.php?pag=".$pag);
    }
    else {
        header("Location: listadoCatalogos.php");
    }
}
