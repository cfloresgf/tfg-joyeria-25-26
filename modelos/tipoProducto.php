<?php
require_once __DIR__.'/bd.php';

class TiposProducto {
    public $idTipo;
    public $nombre;
    public $imagen;
    

    public static function listado() {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM tiposProducto");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $tipos = [];
        while ($tipo = $res->fetch_object('TiposProducto')) {
            $tipos[] = $tipo;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $tipos;
    }

}