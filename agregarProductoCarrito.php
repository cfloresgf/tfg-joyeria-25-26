<?php 
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

if($usuario->admin==0) {

    if(isset($_POST['idProducto'])) {
        
        //Si existe un carrito activo, lo coge y si no lo crea
        $carrito = Carrito::comprobarCarrito($usuario->idUsuario);
        $idCarrito = $carrito->idCarrito;
        

        //Si existe una linea carrito con ese carrito y ese producto, no se crea una nueva sino que se añade una unidad más
        $linea = LineaCarrito::comprobarLineaCarrito($idCarrito,$_POST['idProducto']);

        if($linea != null) {
            $linea->cantidad ++;
            $linea->actualizar();
        }
        else {
            // Crear un nuevo objeto de la clase correspondiente
            $lineaCarrito = new LineaCarrito();
            
            // Establecer los valores del objeto
            $lineaCarrito->idUsuario = $usuario->idUsuario;
            $lineaCarrito->idCarrito = $idCarrito;
            $lineaCarrito->idProducto = $_POST['idProducto'];
            $lineaCarrito->cantidad = 1;
            
            // Insertar la línea del carrito
            $lineaCarrito->insertar();
        }


        //Añadir precio del producto al importeTotal del carrito
        $producto = Producto::cargar($_POST['idProducto']);
        $carrito->importeTotal += $producto->precio;
        if($carrito->importeTotal>100) {
            $carrito->envioGratuito = 1;
        }
        else {
            $carrito->envioGratuito = 0;
        }
        $carrito->actualizarImporte();

        // Devolver una respuesta al cliente
        $respuesta = "🛒 Producto agregado al carrito";
        $ok = true;

    } else {
        // Si no se reciben los datos necesarios, devolver un mensaje de error
        $respuesta = "Error al agregar el producto al carrito";
        $ok = false;
    }
}
else {
    $respuesta = "Los administradores no pueden agregar productos al carrito";
    $ok = false;
}

header("Content-Type: application/json");
echo json_encode([
    "ok" => $ok,
    "mensaje" => $respuesta
]);