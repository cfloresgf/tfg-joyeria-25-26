<?php
require_once __DIR__ . '/lib/funciones.php';
session_start();
$tituloPagina = "Flor de Gimeno | Iniciar sesión";
include __DIR__ . '/include/cabecera.php';
?>

<div class="row">
    <div class="col-md-4 offset-md-4">
        <form action="doLogin.php" method="POST">
            <h1 class="titulo-login">Iniciar sesión</h1>
            <?php if(isset($_SESSION['error-login'])): ?>
                <div class="alert alert-danger">
                    <?= e($_SESSION['error-login']) ?>
                </div>
            <?php
                unset($_SESSION['error-login']);
                endif;
            ?>

            <!--Email-->
            <div class="mb-3">
                <label class="form-label" for="login">
                    Email:
                </label>
                <input type="text" id="login" name="login" class="form-control"/>
            </div>

            <!--Contraseña-->
            <div class="mb-3 position-relative">
                <label class="form-label" for="pwd">
                    Contraseña:
                </label>
                <input type="password" id="pwd" name="pwd" class="form-control input-pwd"/>
                <i class="bi bi-eye-slash mostrar-pwd" id="togglePwd"></i>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-cancelar">
                    Iniciar sesión
                </button>
            </div>
        </form>

        <!--Resgistrarse-->
        <p class="mt-5 text-center">
            ¿Todavía no tienes cuenta? 
            <a href="registro.php" class="enlace-registro">Regístrate</a>
        </p>
    </div>
</div>

<script>
    const passwordInput = document.getElementById("pwd");
    const togglePassword = document.getElementById("togglePwd");

    togglePassword.addEventListener("click", function () {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;

        this.classList.toggle("bi-eye");
        this.classList.toggle("bi-eye-slash");
    });
</script>
