document.addEventListener("DOMContentLoaded", function () {
  const headerContainer = document.querySelector("header .container");
  if (headerContainer) {
    const menuToggle = document.createElement("button");
    menuToggle.className = "mobile-menu-toggle";
    menuToggle.innerHTML = "☰ Menu";
    headerContainer.prepend(menuToggle);
    menuToggle.addEventListener("click", function () {
      document.querySelector("nav")?.classList.toggle("active");
    });
  }

  document.querySelectorAll(".photo-item").forEach((item) => {
    item.addEventListener("mouseenter", function () {
      const info = this.querySelector(".photo-info");
      if (info) info.style.transform = "translateY(0)";
    });
    item.addEventListener("mouseleave", function () {
      const info = this.querySelector(".photo-info");
      if (info) info.style.transform = "translateY(100%)";
    });
  });

  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      const pw = this.querySelector('input[type="password"]');
      if (pw && pw.value.length < 6) {
        e.preventDefault();
        alert("Password must be at least 6 characters");
      }
    });
  });
});
