<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <!--Poner icono de la pagina web-->
    <link rel="icon" href="img/logo_principal.png" type="image/svg+xml" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!--Esta parte es nueva_contrasena.php-->
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-2" style=" background: linear-gradient(135deg, #25cdfcff , #2ad867ff);">
        <div class="container">
            <!-- Logo -->

            <a class="navbar-brand fw-bold text-primary fs-3 text-dark" href="#">
                <img src="img/icono_dashboard.png" height="50" alt="expatul Logo" class="logo-img">
            </a>
            <div class="d-flex align-items-center ms-auto">
                <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">

                        <h4 class="text-center mb-3">Crear nueva contraseña</h4>
                        <p class="text-muted text-center">
                            Ingresa una contraseña segura para tu cuenta.
                        </p>

                        <form method="POST" action="controladores/procesar_nueva_contrasena.php">

                            <input type="hidden" name="usId"
                                value="<?= htmlspecialchars($_GET['usId'] ?? '') ?>">
                                <input type="hidden" name="tipo"
                                value="<?= htmlspecialchars($_GET['tipo'] ?? '') ?>">
                            <input type="hidden" name="token"
                                value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control" required minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        👁️
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirm" id="password_confirm"
                                        class="form-control" required minlength="8">
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

                            <button id="btnRegistrar" class="btn btn-primary w-100 py-1 mt-1" disabled>
                                Guardar contraseña
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const togglePassword2 = document.getElementById('togglePassword2');
        const passwordInput2 = document.getElementById('password_confirm');
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
</body>

</html>