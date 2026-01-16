<?php
require_once __DIR__.'/bd.php';

class Usuario {
    public $idUsuario;
    public $nombre;
    public $email;
    public $pwd;
    public $telefono;
    public $admin;


    public static function cargaLogin($login) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM usuarios
                WHERE email=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("s", $login);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $usuario = $res->fetch_object('Usuario');
        $res->free();
        $st->close();
        $bd->close();
        return $usuario;
    }

    public static function cuenta(){
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num
            FROM usuarios u;");

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

    public static function listado($pag) {
        $tamPagina = TAM_PAGINA2;
        $offset = ($pag-1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT u.* FROM usuarios u LIMIT ?,?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", $offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $usuarios = [];
        while ($usuario = $res->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $usuarios;
    }

    public static function cargar($id){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM usuarios Where idUsuario=?");
        if ($st === false) {
            die($bd->error);
        }
        $st->bind_param('i', $id);
        $ok = $st->execute();
        if ($ok === false) {
            die($bd->error);
        }
        $res = $st->get_result();
        $usuario = $res->fetch_object('Usuario');
        $res->free();
        $st->close();
        $bd->close();
        return $usuario;
    }

    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO usuarios
                (nombre,email,pwd,telefono,admin) 
                VALUES (?,?,?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssii", 
                $this->nombre, 
                $this->email, 
                $this->pwd,
                $this->telefono,
                $this->admin);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idUsuario = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public function actualizar() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE usuarios SET
                nombre=?, email=?, pwd=?, telefono=?, admin=? 
                WHERE idUsuario=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssiii", 
                $this->nombre, 
                $this->email, 
                $this->pwd,
                $this->telefono,
                $this->admin,
                $this->idUsuario);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public function guardar() {
        if ($this->idUsuario) {
            $this->actualizar();
        }
        else {
            $this->insertar();
        }
    }

    public static function eliminar($idUsuario) {
        $bd = abrirBD();
        $st = $bd->prepare("DELETE FROM usuarios WHERE idUsuario=?;");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idUsuario);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

}