//Para mostrar u ocultar contraseña
const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("contrasenaActual");
const togglePassword2 = document.getElementById("togglePassword2");
const passwordInput2 = document.getElementById("contrasenaConfirmar");
const togglePassword3 = document.getElementById("togglePassword3");
const passwordInput3 = document.getElementById("contrasenaNueva");
togglePassword2.addEventListener("click", function () {
  const type =
    passwordInput2.getAttribute("type") === "password" ? "text" : "password";
  passwordInput2.setAttribute("type", type);
  this.textContent = type === "password" ? "👁️" : "🙈";
});
togglePassword3.addEventListener("click", function () {
  const type =
    passwordInput3.getAttribute("type") === "password" ? "text" : "password";
  passwordInput3.setAttribute("type", type);
  this.textContent = type === "password" ? "👁️" : "🙈";
});

togglePassword.addEventListener("click", function () {
  const type =
    passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);
  this.textContent = type === "password" ? "👁️" : "🙈";
});

///Para el actualizar_perfil.php
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#editarPerfilModal form");
  const alertBox = document.getElementById("alertPerfil");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    alertBox.className = "alert d-none";
    alertBox.innerHTML = "";

    const formData = new FormData(form);

    try {
      const fileInput = form.querySelector('input[type="file"]');

      if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 1781760; // 1.7 MB

        if (file.size > maxSize) {
          showAlert("danger", ["La imagen no debe superar 1.7 MB"]);
          return;
        }
      }

      const response = await fetch("controladores/actualizar_perfil.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "error") {
        showAlert("danger", data.messages);
      } else {
        showAlert("success", data.messages);

        // cerrar modal después de 2s
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("editarPerfilModal")
          );
          modal.hide();
          location.reload(); // opcional (para refrescar datos)
        }, 2000);
      }
    } catch (err) {
      showAlert("danger", ["Error de conexión"]);
    }
  });

  function showAlert(type, messages) {
    alertBox.className = `alert alert-${type}`;
    alertBox.innerHTML = messages.map((m) => `<div>${m}</div>`).join("");

    // auto cerrar alert
    setTimeout(() => {
      alertBox.classList.add("fade");
      setTimeout(() => {
        alertBox.className = "alert d-none";
      }, 500);
    }, 4000);
  }
});

