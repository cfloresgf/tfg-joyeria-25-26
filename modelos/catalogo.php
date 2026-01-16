<?php
require_once __DIR__.'/bd.php';

class Catalogo {
    public $idCatalogo;
    public $nombre;
    public $temporada;
    public $año;
    public $archivoPDF;
    public $activo;


    public function getRutaFichero() {
        return "ficheros/" . $this->idCatalogo . '_' . $this->archivoPDF;
    }
  

    public static function listado($pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT c.* FROM catalogos c
            WHERE activo = 1
            ORDER BY idCatalogo DESC
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
        $catalogos = [];
        while ($catalogo = $res->fetch_assoc()) {
            $catalogos[] = $catalogo;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $catalogos;
    }

    public static function cuenta(){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM catalogos c WHERE activo = 1;");

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

    public static function listadoFiltrado($temporada, $año, $pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag - 1) * $tamPagina;
        $bd = abrirBD();

        $sql = "SELECT * FROM catalogos WHERE activo = 1";
        $params = [];
        $types = "";

        if (!empty($temporada)) {
            $sql .= " AND temporada = ?";
            $params[] = $temporada;
            $types .= "s";
        }

        if (!empty($año)) {
            $sql .= " AND año = ?";
            $params[] = $año;
            $types .= "i";
        }

        $sql .= " ORDER BY idCatalogo DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $tamPagina;
        $types .= "ii";

        $st = $bd->prepare($sql);
        if ($st === false) {
            die($bd->error);
        }

        $st->bind_param($types, ...$params);
        $st->execute();
        $res = $st->get_result();

        $catalogos = [];
        while ($catalogo = $res->fetch_assoc()) {
            $catalogos[] = $catalogo;
        }

        $res->free();
        $st->close();
        $bd->close();

        return $catalogos;
    }

    public static function cuentaFiltrado($temporada, $año) {
        $bd = abrirBD();

        $sql = "SELECT COUNT(*) as num FROM catalogos WHERE activo = 1";
        $params = [];
        $types = "";

        if (!empty($temporada)) {
            $sql .= " AND temporada = ?";
            $params[] = $temporada;
            $types .= "s";
        }

        if (!empty($año)) {
            $sql .= " AND año = ?";
            $params[] = $año;
            $types .= "i";
        }

        $st = $bd->prepare($sql);
        if ($st === false) {
            die($bd->error);
        }

        if (!empty($params)) {
            $st->bind_param($types, ...$params);
        }

        $st->execute();
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
        $st = $bd->prepare("SELECT c.* FROM catalogos c
            ORDER BY idCatalogo DESC
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
        $catalogos = [];
        while ($catalogo = $res->fetch_assoc()) {
            $catalogos[] = $catalogo;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $catalogos;
    }

    public static function cuentaAdministrativo() {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT COUNT(*) as num FROM catalogos c;");

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

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM catalogos WHERE idCatalogo=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $catalogo = $res->fetch_object('Catalogo');
        $res->free();
        $st->close();
        $bd->close();
        return $catalogo;
    }

    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO catalogos
                (nombre,temporada,año,archivoPDF,activo) 
                VALUES (?,?,?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ssssi", 
                $this->nombre, 
                $this->temporada, 
                $this->año,
                $this->archivoPDF,
                $this->activo);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idCatalogo = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public function actualizar() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE catalogos SET
                nombre=?, temporada=?, año=?, archivoPDF=?, activo=?
                WHERE idCatalogo=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ssssii", 
                $this->nombre, 
                $this->temporada, 
                $this->año,
                $this->archivoPDF,
                $this->activo,
                $this->idCatalogo);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public function guardar() {
        if ($this->idCatalogo) {
            $this->actualizar();
        }
        else {
            $this->insertar();
        }
    }

    public static function eliminar($idCatalogo) {
        $bd = abrirBD();
        $st = $bd->prepare("DELETE FROM catalogos WHERE idCatalogo=?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idCatalogo);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public static function listadoAños() {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT DISTINCT año FROM catalogos WHERE activo = 1 ORDER BY año DESC");
        if ($st === false) {
            die($bd->error);
        }

        $st->execute();
        $res = $st->get_result();

        $años = [];
        while ($fila = $res->fetch_assoc()) {
            $años[] = $fila['año'];
        }

        $res->free();
        $st->close();
        $bd->close();

        return $años;
    }

}