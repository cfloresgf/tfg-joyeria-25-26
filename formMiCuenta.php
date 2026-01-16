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

$menu = "usuario";

if($usuario->admin == 1) {
    $esAdmin = true;
}
else {
    $esAdmin = false;
}

if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $usuario = $_SESSION['datos'];
    $id = $usuario->idUsuario;
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
}
else {
    $errores = [];
}

$tituloPagina = "Flor de Gimeno | Mi cuenta";
$titulo = "Mi cuenta";

include __DIR__.'/include/cabecera.php';
include __DIR__.'/include/menu.php';
?>


<div class="row" style="justify-content: center;">

    <h3 id="tituloFormularios"><?=$titulo?></h3>

    <form action="guardarMiCuenta.php?id=<?= e($usuario->idUsuario)?>"
        method="POST" class="row g-3" style="margin-top: -5px; margin-bottom: 20px; width: 80%;">

        <input type="hidden" value="<?= e($usuario->idUsuario)?>" name="idUsuario" id="idUsuario"/>

        <!--Nombre-->
        <div class="col-md-12">
            <label class="form-label" for="nombre">
                Nombre:
            </label>
            <input type="text" id="nombre" name="nombre" class="form-control
                <?php if (isset($errores['nombre'])): echo 'is-invalid'; endif;?>"
                value="<?= e($usuario->nombre)?>"/>
            <?php if (isset($errores['nombre'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['nombre']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!--Email-->
        <div class="col-md-12">
            <label class="form-label" for="email">
                Email:
            </label>
            <input type="email" id="email" name="email" class="form-control
                <?php if (isset($errores['email'])): echo 'is-invalid'; endif;?>"
                value="<?= e($usuario->email)?>" disabled/> 
        </div>

        <!--Contraseña-->
        <div class="col-md-6 position-relative">
            <label class="form-label" for="pwd">
                Contraseña:
            </label>
            <input type="password" id="pwd" name="pwd" class="form-control input-pwd
                <?php if (isset($errores['pwd'])): echo 'is-invalid'; endif;?>"/>
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
                Número de teléfono:
            </label>
            <input type="tel" id="telefono" name="telefono" class="form-control
                <?php if (isset($errores['telefono'])): echo 'is-invalid'; endif;?>"
                value="<?= e($usuario->telefono)?>"/>
            <?php if (isset($errores['telefono'])): ?>
                <div class="invalid-feedback">
                    <?= e($errores['telefono']) ?>
                </div>
            <?php endif; ?>   
        </div>

        <!--Botones de guardar y cancelar-->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-ok" id="btnGuardarUsuario">
                Guardar
            </button>
            <a href="index.php" class="btn btn-cancelar">
                Cancelar
            </a>
        </div>
        
    </form>
</div>

<?php include __DIR__.'/include/scripts.php';?>
<script>
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
