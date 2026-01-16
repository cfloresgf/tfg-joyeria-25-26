<?php
require_once __DIR__.'/bd.php';

class Producto {
    public $idProducto;
    public $nombre;
    public $descripcion;
    public $precio;
    public $imagen;
    public $idTipo;


    public function getRutaFoto() {
        return "fotos/" . $this->idProducto . '_' . $this->imagen;
    }
  

    public static function listado($pag) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            ORDER BY idProducto
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('ii',$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function cuenta(){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM productos p;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['num'];
    }

    public static function listadoFiltrado($idTipo, $pag) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.idTipo=? ORDER BY idProducto
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii", $idTipo, $offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function cuentaFiltrado($idTipo){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM productos p
            WHERE p.idTipo=?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $idTipo);
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['num'];
    }

    public static function listadoAdministrativo($pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            ORDER BY idProducto
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('ii',$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }    

    public static function listadoBusqueda($pag, $nombre) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.nombre LIKE ?
            ORDER BY idProducto
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('sii',$nombreBusqueda,$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function cuentaBusqueda($nombre){
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM productos p
            WHERE p.nombre LIKE ?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $nombreBusqueda);
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['num'];
    }

    public static function listadoFiltradoBusqueda($pag, $idTipo, $nombre) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.nombre LIKE ? AND p.idTipo=?
            ORDER BY idProducto
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('siii',$nombreBusqueda,$idTipo,$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function cuentaFiltradoBusqueda($idTipo, $nombre){
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM productos p
            WHERE p.nombre LIKE ? AND p.idTipo = ?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("si", $nombreBusqueda,$idTipo);
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['num'];
    }

    public static function listadoOrdenado($pag, $orden) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            ORDER BY precio $orden
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('ii',$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }
    
    public static function listadoFiltradoOrdenado($idTipo, $pag, $orden) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.idTipo=? ORDER BY precio $orden
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii", $idTipo, $offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function listadoBusquedaOrdenado($pag, $nombre, $orden) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.nombre LIKE ?
            ORDER BY precio $orden
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('sii',$nombreBusqueda,$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function listadoFiltradoBusquedaOrdenado($pag, $idTipo, $nombre, $orden) {
        $tamPagina = TAM_PAGINA;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $nombreBusqueda = "%$nombre%";
        $st = $bd->prepare("SELECT p.*, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t ON p.idTipo=t.idTipo
            WHERE p.nombre LIKE ? AND p.idTipo=?
            ORDER BY precio $orden
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('siii',$nombreBusqueda,$idTipo,$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $productos = [];
        while ($producto = $res->fetch_assoc()) {
            $productos[] = $producto;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $productos;
    }

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM productos Where idProducto=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $producto = $res->fetch_object('Producto');
        $res->free();
        $st->close();
        $bd->close();
        return $producto;
    }

    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO productos
                (nombre,descripcion,precio,imagen,idTipo) 
                VALUES (?,?,?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ssdsi", 
                $this->nombre, 
                $this->descripcion, 
                $this->precio,
                $this->imagen,
                $this->idTipo);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idProducto = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public function actualizar() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE productos SET
                nombre=?, descripcion=?, precio=?, imagen=?, idTipo=?
                WHERE idProducto=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ssdsii", 
                $this->nombre, 
                $this->descripcion, 
                $this->precio,
                $this->imagen,
                $this->idTipo,
                $this->idProducto);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public function guardar() {
        if ($this->idProducto) {
            $this->actualizar();
        }
        else {
            $this->insertar();
        }
    }

    public static function eliminar($idProducto) {
        $bd = abrirBD();
        $st = $bd->prepare("DELETE FROM productos WHERE idProducto=?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idProducto);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function verificaEliminacion($idProducto){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM lineacarrito l
            WHERE l.idProducto=?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $idProducto);
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['num'];
    }

}