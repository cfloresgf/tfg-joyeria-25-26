<?php
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__.'/lib/funciones.php';
session_start();


if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
}

$menu = "administracion";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

//Comprobar si viene de nuevo o de editar y validaciones
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $u = $_SESSION['datos'];
    $id = $u->idUsuario;
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
}
else if (isset($_GET['id'])) {
    $errores = [];
    $id = $_GET['id'];
    $u = Usuario::cargar($id);
    $pag = $_GET['pag'];
}
else {
    $errores = [];
    $id = 0;
    $u = new Usuario();
}


if ($id == 0) {
    $tituloPagina = "Flor de Gimeno | Nuevo Usuario";
    $titulo = "Nuevo Usuario";
}
else {
    $tituloPagina = "Flor de Gimeno | Edición de Usuario";
    $titulo = "Edición de Usuario";
}


include __DIR__.'/error.php';
include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<div class="row" style="justify-content: center;">

    <h3 id="tituloFormularios"><?=$titulo?></h3>

    <!--Alert de advertencia si ya existe un usuario con ese email-->
    <div class="alert mensajeAlerta mb-3 d-none" id="error-duplicado" style="margin-top: 10px; width: 80%;">
        <span id="iconoMensajeAlerta">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </span>
        <span class="message">
            Ya existe un usuario con este email
        </span>
    </div>

    <form action="guardarUsuario.php?id=<?= e($u->idUsuario)?><?php if (isset($_GET['id'])):?>&pag=<?=$pag?><?php endif;?>"
        method="POST" class="row g-3" style="margin-top: -5px; margin-bottom: 20px; width: 80%;">

        <input type="hidden" value="<?= e($u->idUsuario)?>" name="idUsuario" id="idUsuario"/>

        <!--Nombre-->
        <div class="col-md-12">
            <label class="form-label" for="nombre">
                Nombre
            </label>
            <input type="text" id="nombre" name="nombre" class="form-control
                <?php if (isset($errores['nombre'])): echo 'is-invalid'; endif;?>"
                placeholder="Nombre:" value="<?= e($u->nombre)?>"/>
            <?php if (isset($errores['nombre'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['nombre']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Email-->
        <div class="col-md-12">
            <label class="form-label" for="email">
                Email
            </label>
            <input type="email" id="email" name="email" class="form-control
                <?php if (isset($errores['email'])): echo 'is-invalid'; endif;?>"
                placeholder="Email:" value="<?= e($u->email)?>"/>
            <?php if (isset($errores['email'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['email']) ?>
                </div>
            <?php endif; ?>  
        </div>

        <!--Contraseña-->
        <div class="col-md-6 position-relative">
            <label class="form-label" for="pwd">
                Contraseña
            </label>
            <input type="password" id="pwd" name="pwd" class="form-control input-pwd
                <?php if (isset($errores['pwd'])): echo 'is-invalid'; endif;?>"
                placeholder="Contraseña:"/>
            <i class="bi bi-eye-slash mostrar-pwd" id="togglePwd"></i>
            <?php if (isset($errores['pwd'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['pwd']) ?>
                </div>
            <?php endif; ?>   
        </div>

        <!--Número de teléfono-->
        <div class="col-md-6">
            <label class="form-label" for="telefono">
                Número de teléfono
            </label>
            <input type="tel" id="telefono" name="telefono" class="form-control
                <?php if (isset($errores['telefono'])): echo 'is-invalid'; endif;?>"
                placeholder="123456789..." value="<?= e($u->telefono)?>"/>
            <?php if (isset($errores['telefono'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['telefono']) ?>
                </div>
            <?php endif; ?>   
        </div>

        <!--Administrador-->
        <div class="col-md-12">
            <input type="checkbox" id="admin" name="admin" 
            <?php if(e($u->admin)) : ?>
                checked
            <?php endif;?>/>    
            <label class="form-label" for="admin">
                Administrador
            </label>           
        </div>

        <!--Botones de guardar y cancelar-->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-ok" id="btnGuardarUsuario">
                Guardar
            </button>
            <a href="listadoUsuarios.php<?php if(isset($_GET['id'])):?>?pag=<?=$pag?><?php endif;?>" class="btn btn-cancelar">
                Cancelar
            </a>
        </div>
        
    </form>
</div>


<?php include __DIR__.'/include/scripts.php';?>

<script>
    //Comprobación de si el email existe o no
   const email = document.getElementById("email");
   const idSeleccionado = 0;
   email.addEventListener("change", function() {
        let formData = new FormData();
        formData.append("email", email.value);
        formData.append("idUsuario", idSeleccionado);
        fetch("validacionEmail.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            const error = document.getElementById("error-duplicado");
            if (msg == "DUPLICADO") {
            error.classList.remove("d-none");
            }
            else {
            error.classList.add("d-none");
            }
        });
    });

    // Mostrar contraseña
    const togglePwd = document.getElementById("togglePwd");
    const pwdInput = document.getElementById("pwd");
    togglePwd.addEventListener("click", function () {
        const type = pwdInput.type === "password" ? "text" : "password";
        pwdInput.type = type;

        this.classList.toggle("bi-eye");
        this.classList.toggle("bi-eye-slash");
    });
</script>


<?php include __DIR__.'/include/pie.php';?>
