<?php
require('PDF/fpdf.php');
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/modelos/producto.php';
require_once __DIR__.'/modelos/lineaCarrito.php';
require_once __DIR__.'/modelos/carrito.php';
require_once __DIR__.'/modelos/pedido.php';
require_once __DIR__.'/lib/funciones.php';
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header('Location: login.php');
    die();
}

$idCarrito = $_GET['idCarrito'];

$carrito = Carrito::cargar($idCarrito);
$lineasCarrito = LineaCarrito::listadoPorCarrito($idCarrito);
$pedido = Pedido::cargarIdCarrito($idCarrito);


// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Título del documento
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Detalles del Pedido', 0, 1, 'C');
$pdf->Ln(8);

// Información del usuario
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nombre: ' . $usuario->nombre, 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $usuario->email, 0, 1);
$pdf->Ln(10);

// Información del pedido
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Productos del Pedido', 0, 1);
$pdf->Ln(5);

//Productos
$pdf->SetFont('Arial', '', 12);
foreach ($lineasCarrito as $linea) {
    $producto = Producto::cargar($linea['idProducto']);
    $subtotal = $linea['cantidad'] * $producto->precio;

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $imagen = $producto->getRutaFoto();
    if (!empty($imagen) && file_exists($imagen)) {
        $pdf->Image($imagen, $x, $y, 30);
        $pdf->SetX($x + 35);
    }

    $pdf->Cell(0, 8, 'Producto: ' . $producto->nombre, 0, 1);
    $pdf->SetX($x + 35);
    $pdf->Cell(0, 8, 'Cantidad: ' . $linea['cantidad'], 0, 1);
    $pdf->SetX($x + 35);
    $pdf->Cell(0, 8, 'Precio: ' . number_format($producto->precio, 2) . ' EUR', 0, 1);
    $pdf->SetX($x + 35);
    $pdf->Cell(0, 8, 'Subtotal: ' . number_format($subtotal, 2) . ' EUR', 0, 1);

    $pdf->Ln(10);
}

//Envío
if($carrito->envioGratuito==0){
    $pdf->Cell(0, 10, 'Gastos de envio: 2.95 EUR', 0, 1);
}
else {
    $pdf->Cell(0, 10, 'Gastos de envio: 0.00 EUR (ENVIO GRATUITO)', 0, 1);
}
$pdf->Ln(5);


// Definir los colores para cada estado
$estadoColores = [
    0 => [255, 165, 0],   // Naranja
    1 => [0, 128, 0],     // Verde
    2 => [255, 0, 0],     // Rojo
    3 => [76, 140, 212],  // Azul
];

$estadoPedido = $pedido->estado;

// Establecer el color del texto según el estado del pedido
list($r, $g, $b) = $estadoColores[$estadoPedido];
$pdf->SetTextColor($r, $g, $b);

// Mostrar el estado del pedido
$estadoNombres = [
    0 => 'Pendiente',
    1 => 'Finalizado',
    2 => 'Cancelado',
    3 => 'Devuelto',
];
$pdf->Cell(0, 10, 'Estado: ' . $estadoNombres[$estadoPedido], 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(10);

//Importe total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Total del Pedido: ' . number_format($carrito->importeTotal, 2) . ' EUR', 0, 1, 'R');

// Enviar el PDF al navegador
$pdf->Output('I', 'Pedido_' . $idCarrito . '.pdf');
