<?php
require_once __DIR__ . '/lib/funciones.php';
require_once __DIR__.'/modelos/usuario.php';
session_start();

if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $u = $_SESSION['datos'];
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
}
else {
    $errores = [];
    $u = new Usuario();
}

$tituloPagina = "Flor de Gimeno | Crear cuenta";
include __DIR__ . '/include/cabecera.php';
?>

<div class="row">
    <div class="col-md-4 offset-md-4">
        <form action="doRegistro.php" method="POST" id="formRegistro">
            <h1 class="titulo-login">Crear cuenta</h1>

            <?php if(isset($_SESSION['errores'])): ?>
                <div class="alert alert-danger">
                    <?= implode("<br>", $_SESSION['errores']) ?>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>

            <!-- Nombre -->
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control
                    <?php if (isset($errores['nombre'])): echo 'is-invalid'; endif;?>"
                    value="<?= e($u->nombre)?>"/>
                <?php if (isset($errores['nombre'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errores['nombre']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label" for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control
                    <?php if (isset($errores['email'])): echo 'is-invalid'; endif;?>"
                    value="<?= e($u->email)?>"/>
                <?php if (isset($errores['email'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errores['email']) ?>
                    </div>
                <?php endif; ?> 
            </div>

            <!-- Alerta email duplicado -->
            <div class="alert mensajeAlerta mb-3 d-none" id="error-duplicado">
                <span id="iconoMensajeAlerta">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </span>
                <span class="message">
                    Ya existe un usuario con este email
                </span>
            </div>

            <!-- Contraseña -->
            <div class="mb-3 position-relative">
                <label class="form-label">Contraseña:</label>
                <input type="password" id="pwd" name="pwd" class="form-control input-pwd
                    <?php if (isset($errores['pwd'])): echo 'is-invalid'; endif;?>"/>
                <i class="bi bi-eye-slash mostrar-pwd" id="togglePwd"></i>
                <?php if (isset($errores['pwd'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errores['pwd']) ?>
                    </div>
                <?php endif; ?> 
                <small class="text-danger d-none" id="pwdCorta">
                    La contraseña debe tener al menos 6 caracteres
                </small>
            </div>

            <!-- Número de teléfono -->
            <div class="mb-3">
                <label class="form-label">Número de teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-control
                    <?php if (isset($errores['telefono'])): echo 'is-invalid'; endif;?>"
                    value="<?= e($u->telefono)?>"/>
                <?php if (isset($errores['telefono'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errores['telefono']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-cancelar">
                    Registrarse
                </button>
            </div>
        </form>

        <!--Login-->
        <p class="mt-5 text-center">
            ¿Ya tienes cuenta? 
            <a href="login.php" class="enlace-registro">Inicia Sesión</a>
        </p>
    </div>
</div>

<script>
    // Comprobación de si el email existe o no
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
            } else {
                error.classList.add("d-none");
            }
        });
    });

    // Validación contraseña
    const pwd = document.getElementById("pwd");
    const errorPwd = document.getElementById("pwdCorta");
    pwd.addEventListener("input", () => {
        errorPwd.classList.toggle("d-none", pwd.value.length >= 6);
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
