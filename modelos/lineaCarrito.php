<?php
require_once __DIR__.'/bd.php';

class lineaCarrito {
    public $idLinea;
    public $idCarrito;
    public $idProducto;
    public $cantidad;


    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO lineaCarrito
                (idCarrito,idProducto,cantidad) 
                VALUES (?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii", 
                $this->idCarrito, 
                $this->idProducto, 
                $this->cantidad);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idLinea = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public function actualizar() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE lineaCarrito SET
                cantidad=? WHERE idLinea=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", 
                $this->cantidad, 
                $this->idLinea);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function eliminar($idLinea) {
        $bd = abrirBD();
        $st = $bd->prepare("DELETE FROM lineaCarrito WHERE idLinea=?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idLinea);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function comprobarLineaCarrito($idCarrito,$idProducto) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM lineaCarrito WHERE idCarrito = ? AND idProducto = ?");
        $st->bind_param('ii', $idCarrito, $idProducto);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        if ($st === false) {
            die($bd->error);
        }

        $fila = $res->fetch_assoc();
        if ($fila) {
            $linea = new LineaCarrito();
            $linea->idLinea   = $fila['idLinea'];
            $linea->idCarrito = $fila['idCarrito'];
            $linea->idProducto = $fila['idProducto'];
            $linea->cantidad  = $fila['cantidad'];
        } else {
            $linea = null;
        }
    
        $res->free();
        $st->close();
        $bd->close();
        return $linea;
    }

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM lineaCarrito Where idLinea=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();

        $fila = $res->fetch_assoc();
        if ($fila) {
            $linea = new LineaCarrito();
            $linea->idLinea   = $fila['idLinea'];
            $linea->idCarrito = $fila['idCarrito'];
            $linea->idProducto = $fila['idProducto'];
            $linea->cantidad  = $fila['cantidad'];
        } else {
            $linea = null;
        }
        
        $res->free();
        $st->close();
        $bd->close();
        return $linea;
    }

    public static function listadoPorCarrito($idCarrito) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM lineaCarrito
            WHERE idCarrito=?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('i',$idCarrito);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $lineas = [];
        while ($linea = $res->fetch_assoc()) {
            $lineas[] = $linea;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $lineas;
    }

}