<?php
require_once __DIR__.'/bd.php';

class Pedido {
    public $idPedido;
    public $idCarrito;
    public $fecha;
    public $estado;


    public static function listadoAdministrativo($pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*, u.nombre as cliente
            FROM pedidos p
            INNER JOIN carritos c ON c.idCarrito=p.idCarrito
            INNER JOIN usuarios u ON u.idUsuario=c.idUsuario
            ORDER BY fechaPedido DESC
            LIMIT ?,?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('ii',$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $pedidos = [];
        while ($pedido = $res->fetch_assoc()) {
            $pedidos[] = $pedido;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $pedidos;
    }

    public static function cuentaAdministrativo(){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM pedidos;");

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

    public static function listadoGeneralDetalles($idPedido) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*,c.*,l.*,pr.* FROM pedidos p
            INNER JOIN carritos c ON c.idCarrito=p.idCarrito
            INNER JOIN lineaCarrito l ON c.idCarrito=l.idCarrito
            INNER JOIN productos pr ON pr.idProducto=l.idProducto
            WHERE idPedido = ?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i",$idPedido);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $pedidos = [];
        while ($pedido = $res->fetch_assoc()) {
            $pedidos[] = $pedido;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $pedidos;
    }

    public static function listadoSimpleFiltradoUsuario($idUsuario,$pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT p.*,c.* FROM pedidos p
            INNER JOIN carritos c ON c.idCarrito=p.idCarrito
            WHERE idUsuario = ?
            ORDER BY fechaPedido DESC
            LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii",$idUsuario,$offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $pedidos = [];
        while ($pedido = $res->fetch_assoc()) {
            $pedidos[] = $pedido;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $pedidos;
    }

    public static function cuentaSimpleFiltradoUsuario($idUsuario){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM pedidos p
            INNER JOIN carritos c ON p.idCarrito=c.idCarrito
            WHERE c.idUsuario=?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $idUsuario);
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

    public static function cuentaProductos($idPedido){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT SUM(cantidad) AS productos FROM pedidos p
        INNER JOIN carritos c ON c.idcarrito = p.idcarrito
        INNER JOIN lineacarrito l ON l.idCarrito = c.idcarrito
        WHERE idPedido = ?;");

        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $idPedido);
        $ok = $st->execute();

        if ($ok === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $res = $st->get_result();

        $datos = $res->fetch_assoc();

        $res->free();
        $st->close();
        $bd->close();
    
        return $datos['productos'];
    }


    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO pedidos
                (idCarrito,fechaPedido,estado) 
                VALUES (?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("isi", 
                $this->idCarrito, 
                $this->fecha, 
                $this->estado);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idPedido = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM pedidos Where idPedido=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $pedido = $res->fetch_object('Pedido');
        $res->free();
        $st->close();
        $bd->close();
        return $pedido;
    }

    public static function cargarIdCarrito($idCarrito){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM pedidos Where idCarrito=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $idCarrito);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $pedido = $res->fetch_object('Pedido');
        $res->free();
        $st->close();
        $bd->close();
        return $pedido;
    }

    public static function enviar($idPedido,$fechaEnvio) {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE pedidos SET fechaEnvio = ?, estado = 1 
            WHERE idPedido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("si",$fechaEnvio,$idPedido);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function cancelar($idPedido,$fechaEnvio) {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE pedidos SET fechaAnulacion = ?, estado = 2 
            WHERE idPedido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("si",$fechaEnvio,$idPedido);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function devolver($idPedido,$fechaEnvio) {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE pedidos SET fechaAnulacion = ?, estado = 3 
            WHERE idPedido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("si",$fechaEnvio,$idPedido);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function listadoGrafico($fecha) {
        $bd = abrirBD();
        $st = $bd->prepare(" SELECT SUM(cantidad) AS total, t.nombre as tipo FROM productos p
            INNER JOIN tiposproducto t on t.idtipo=p.idtipo
            INNER JOIN lineaCarrito lc ON p.idProducto = lc.idProducto
            INNER JOIN carritos c ON lc.idCarrito = c.idCarrito
            INNER JOIN pedidos pd ON c.idCarrito = pd.idCarrito
            WHERE DATE(pd.fechaPedido) = ?
            GROUP BY p.idtipo");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param('s',$fecha);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $pedidos = [];
        while ($pedido = $res->fetch_assoc()) {
            $pedidos[] = array(
                'tipo' => $pedido['tipo'],
                'total' => $pedido['total']
            );
        }
        $res->free();
        $st->close();
        $bd->close();
        return $pedidos;
    }

}