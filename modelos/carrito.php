<?php
require_once __DIR__.'/bd.php';

class Carrito {
    public $idCarrito;
    public $idUsuario;
    public $importeTotal;
    public $activo;
    public $envioGratuito;


    public static function listado($idUsuario) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT c.*, l.*, p.* FROM carritos c
            INNER JOIN lineaCarrito l ON l.idCarrito=c.idCarrito
            INNER JOIN productos p ON p.idProducto=l.idProducto
            WHERE activo = 1 AND idUsuario = ?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('i',$idUsuario);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $carrito = [];
        while ($producto = $res->fetch_assoc()) {
            $carrito[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $carrito;
    }

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM carritos Where idCarrito=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $carrito = $res->fetch_object('Carrito');
        $res->free();
        $st->close();
        $bd->close();
        return $carrito;
    }

    public function actualizarImporte() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE carritos SET
                importeTotal=?, envioGratuito=? WHERE idCarrito=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("dii", 
                $this->importeTotal,
                $this->envioGratuito, 
                $this->idCarrito);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public function actualizarEstado() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE carritos SET
                activo=? WHERE idCarrito=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", 
                $this->activo, 
                $this->idCarrito);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function comprobarCarrito($idUsuario) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM carritos WHERE idUsuario = ? AND activo = 1");
        $st->bind_param('i', $idUsuario);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        if ($st === false) {
            die($bd->error);
        }
    
        if ($res->num_rows > 0) {  
            // Si encuentra un carrito activo, lo devuelve
            $carrito = $res->fetch_object('Carrito');
        } else {
            // Si no encunetra un carrito activo, inserta uno nuevo
            $insertQuery = "INSERT INTO carritos (idUsuario, importeTotal, activo, envioGratuito) VALUES ($idUsuario, 0, 1, 0)";
            $insertResult = $bd->query($insertQuery);
    
            if ($insertResult === false) {
                die($bd->error);
            }
    
            // Obtiene el ID del carrito insertado
            $carritoId = $bd->insert_id;
    
            // Crea un objeto Carrito con el ID
            $carrito = new Carrito();
            $carrito->idCarrito = $carritoId;
            $carrito->idUsuario = $idUsuario;
            $carrito->activo = 1;
        }
    
        $res->free();
        $st->close();
        $bd->close();
    
        return $carrito;
    }

}