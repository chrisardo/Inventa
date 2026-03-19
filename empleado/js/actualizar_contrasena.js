$(document).ready(function () {
  $("#formEditarContrasena").on("submit", function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      url: "controladores/actualizar_contrasena.php",
      type: "POST",
      data: formData,
      success: function (response) {
        $("#contrasenaMessages").html(response);

        if (response.includes("Contraseña actualizada correctamente")) {
          $("#formEditarContrasena")[0].reset();
        }
      },
      error: function () {
        $("#contrasenaMessages").html(
          '<div class="alert alert-danger">Ocurrió un error. Intente nuevamente.</div>'
        );
      },
    });
  });
});
