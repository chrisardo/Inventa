<?php
//Llamar al procesar_login.php
include 'controladores/procesar_login.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventa - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>

<body style=" background: linear-gradient(135deg, #2ad867ff, #25cdfcff);">

    <div class="container login-container d-flex justify-content-center align-items-center vh-100">
        <div class="login-card">
            <div class="login-left">
                <!-- Aquí puedes agregar un logo o imagen si lo deseas -->
                <img src="img/icono_dashboard.png" alt="Inventa Logo" class="mb-4" style="width:360px; height:200px;">
                <p>Por favor, inicia sesión para continuar.</p>
            </div>
            <div class="login-right">
                <h3 class="mb-4">Iniciar sesión</h3>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username o Email</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su usuario o correo">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                👁️
                            </button>
                        </div>
                    </div>


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Recordar</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Ingresar</button>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                </form>
                <!-- Puedes agregar enlaces adicionales aquí, como "¿Olvidaste tu contraseña? y registrarse" -->
                <div class="mt-3 text-center ">
                    <a href="recuperar_cuenta.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a> |
                    <a href="registro_cuenta.php" class="text-decoration-none">Registrarse</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>