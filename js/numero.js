//Toda esta parte es de js/numero.js
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".kpi-number").forEach((el) => {
    const target = parseFloat(el.dataset.value);
    const isDecimal = el.classList.contains("kpi-decimal");

    const duration = 1200;
    const start = performance.now();

    function animate(now) {
      const progress = Math.min((now - start) / duration, 1);
      let value = progress * target;

      if (!isDecimal) value = Math.round(value);

      el.textContent = isDecimal
        ? value.toLocaleString("es-PE", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          })
        : Math.round(value).toLocaleString("es-PE");

      if (progress < 1) requestAnimationFrame(animate);
      else aplicarIcono();
    }

    function aplicarIcono() {
      if (el.id !== "kpi-ganancia") return;

      const icon = document.getElementById("icono-ganancia");
      if (!icon) return;

      if (target > 0) {
        icon.className = "fas fa-arrow-up kpi-up ms-2";
      } else if (target < 0) {
        icon.className = "fas fa-arrow-down kpi-down ms-2";
      } else {
        icon.className = "";
      }
    }

    requestAnimationFrame(animate);
  });
});
