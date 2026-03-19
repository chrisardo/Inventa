<?php
//Llamar al procesar_login.php
include 'controladores/procesar_registro_usuario.php';
//include 'controladores/procesar_modal_terminos.php'
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inventa - Registro</title>
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <link rel="stylesheet" href="css/login.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body style=" background: linear-gradient(135deg, #2ad867ff, #25cdfcff);">
    <div class="container login-container d-flex justify-content-center align-items-center">
        <div class="login-card">
            <div class="login-left">
                <!-- Aquí puedes agregar un logo o imagen si lo deseas -->
                <img src="img/icono_dashboard.png" alt="Inventa Logo" class="mb-4" style="width:370px; height:200px;">
                <h2> </h2>
                <p>Por favor, registre una cuenta.</p>
            </div>
            <div class="login-right py-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h3 class="mb-0">Registrar cuenta</h3>

                    <a href="login.php"
                        class="d-flex justify-content-center align-items-center bg-danger text-white fw-bold"
                        style="width:28px; height:28px; border-radius:4px; text-decoration:none;"
                        aria-label="Cerrar y volver al login">
                        ×
                    </a>
                </div>
                <form method="POST" enctype="multipart/form-data">
              <!-- Imagen -->
                    <div class="mb-0">
                        <div class="card border-0 shadow-sm">
                            <!-- Input -->
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">
                                    <i class="bi bi-image"></i>
                                </span>
                                <input
                                    type="file"
                                    name="imagen"
                                    id="imagen"
                                    class="form-control"
                                    accept="image/png, image/jpeg" required>
                            </div>

                            <div class="form-text text-danger" style="font-size: 0.75rem;">
                                Formatos permitidos: JPG, PNG · Tamaño máximo: 1.8 MB
                            </div>
                            <div class="card-body p-1">
                                <!-- Vista previa -->
                                <div id="previewImagen" class="mt-0 d-none">
                                    <div class="row align-items-center g-3">

                                        <!-- Imagen -->
                                        <div class="col-auto">
                                            <div class="border rounded p-1 bg-light">
                                                <img
                                                    id="previewImg"
                                                    class="img-fluid rounded"
                                                    style="width: 60px; height: 45px; object-fit: cover;">
                                            </div>
                                        </div>

                                        <!-- Detalles -->
                                        <div class="col">
                                            <ul class="list-group list-group-flush small">
                                                <!--<li class="list-group-item px-0">
                                                        <i class="bi bi-file-earmark-text text-success me-2"></i>
                                                        <strong>Nombre:</strong>
                                                        <span id="imgNombre"></span>
                                                    </li>-->
                                                <li class="list-group-item px-0">
                                                    <i class="bi bi-aspect-ratio text-info me-2"></i>
                                                    <strong>Tipo:</strong>
                                                    <span id="imgTipo"></span>
                                                </li>
                                                <li class="list-group-item px-0">
                                                    <i class="bi bi-hdd text-warning me-2"></i>
                                                    <strong>Tamaño:</strong>
                                                    <span id="imgSize"></span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">

                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Empresa" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-person"></i></span>
                                <input type="number" min="0" class="form-control" id="ruc" name="ruc" placeholder="Ruc" required>
                            </div>
                        </div>
                    </div>
                    <!--username + celular -->
                    <div class="row g-2 mb-2">

                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-telephone"></i></span>
                                <input type="number" min="0" class="form-control" id="celular" name="celular" placeholder="Celular" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">

                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                            </div>
                        </div>
                    </div>

                    <!-- Password + Confirm -->
                    <div class="row g-2 mb-2">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña"  required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    👁️
                                </button>
                            </div>
                        </div>

                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="contrasena_repetir" name="contrasena_repetir" placeholder="Confirmar Contraseña" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                    👁️
                                </button>
                            </div>
                        </div>
                        <!-- Seguridad de contraseña -->
                        <div class="mt-0">
                            <!--<div class="progress mb-1" style="height: 3px;">
                                <div id="strengthBar" class="progress-bar" style="width: 0%"></div>
                            </div>-->
                            <small id="strengthText" class="fw-bold small" style="font-size: 0.75rem;"></small>

                            <ul class="mt-0 mb-0 list-unstyled" style="font-size: 0.72rem; line-height:1.1;">
                                <li id="ruleUpper" class="text-danger">❌ 1 letra mayúscula</li>
                                <li id="ruleLower" class="text-danger">❌ 1 letra minúscula</li>
                                <li id="ruleNumber" class="text-danger">❌ Por lo menos 1 úmero</li>
                                <li id="ruleSpecial" class="text-danger">❌ 1 carácter especial (@$!%*?&.#_-)</li>
                                <li id="ruleMatch" class="text-danger">❌ Las contraseñas coinciden</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Checkbox -->
                    <div class="form-check text-start mb-1 text-white " style="margin-top:-12px; ">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="terminos"
                            name="terminos"
                            required>

                        <label class="form-check-label text-success" for="terminos">
                            Acepto los
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTerminos"
                                onclick="event.preventDefault();"
                                class="fw-bold text-decoration-underline text-success">
                                Términos
                            </a>
                            y la
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalPrivacidad"
                                onclick="event.preventDefault();"
                                class="fw-bold text-decoration-underline text-success">
                                Política de Privacidad
                            </a>
                        </label>
                    </div>
                    <!-- Submit -->
                    <!--<button class="btn btn-success w-100 py-1  mb-0 fw-bold">Registrarse </button>-->
                    <button id="btnRegistrar" class="btn btn-success w-100 py-1 mt-1 fw-bold" disabled>Registrarse</button>
                    <!-- Mensaje -->
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?= $tipoAlerta ?> mt-2 ">
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
        include 'modal/modal_terminos_condiciones.php';
        include 'modal/modal_politica_privacidad.php';
        ?>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('contrasena');
        const togglePassword2 = document.getElementById('togglePassword2');
        const passwordInput2 = document.getElementById('contrasena_repetir');
        togglePassword2.addEventListener('click', function() {
            const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput2.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
        //const strengthBar = document.getElementById("strengthBar");
        const strengthText = document.getElementById("strengthText");
        const btnRegistrar = document.getElementById("btnRegistrar");

        const rules = {
            upper: document.getElementById("ruleUpper"),
            lower: document.getElementById("ruleLower"),
            number: document.getElementById("ruleNumber"),
            special: document.getElementById("ruleSpecial"),
            match: document.getElementById("ruleMatch"),
        };

        passwordInput.addEventListener("input", validatePasswords);
        passwordInput2.addEventListener("input", validatePasswords);

        function validatePasswords() {
            const value = passwordInput.value;
            const value2 = passwordInput2.value;

            const hasUpper = /[A-Z]/.test(value);
            const hasLower = /[a-z]/.test(value);
            const hasNumber = /\d/.test(value);
            const hasSpecial = /[@$!%*?&.#_-]/.test(value);
            const match = value !== "" && value === value2;

            updateRule(rules.upper, hasUpper);
            updateRule(rules.lower, hasLower);
            updateRule(rules.number, hasNumber);
            updateRule(rules.special, hasSpecial);
            updateRule(rules.match, match);

            const strength = [hasUpper, hasLower, hasNumber, hasSpecial].filter(Boolean).length;
            updateStrength(strength);

            // Activar botón solo si todo está correcto
            if (hasUpper && hasLower && hasNumber && hasSpecial && match) {
                btnRegistrar.disabled = false;
            } else {
                btnRegistrar.disabled = true;
            }
        }

        function updateRule(element, condition) {
            if (condition) {
                element.classList.remove("text-danger");
                element.classList.add("text-success");
                element.innerHTML = element.innerHTML.replace("❌", "✅");
            } else {
                element.classList.remove("text-success");
                element.classList.add("text-danger");
                element.innerHTML = element.innerHTML.replace("✅", "❌");
            }
        }

        function updateStrength(level) {

            if (level === 0) {
                strengthText.textContent = "";
                strengthText.className = "fw-bold";
                return;
            }

            if (level <= 1) {
                strengthText.textContent = "Seguridad: Débil";
                strengthText.className = "text-danger fw-bold";
            } else if (level <= 3) {
                strengthText.textContent = "Seguridad: Media";
                strengthText.className = "text-warning fw-bold";
            } else {
                strengthText.textContent = "Seguridad: Fuerte";
                strengthText.className = "text-success fw-bold";
            }
        }
    </script>
    <script src="js/visualizar_imagen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>