// Mobile menu toggle
document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.createElement("button");
  menuToggle.className = "mobile-menu-toggle";
  menuToggle.innerHTML = "☰ Menu";
  document.querySelector("header .container").prepend(menuToggle);

  menuToggle.addEventListener("click", function () {
    document.querySelector("nav").classList.toggle("active");
  });

  // Photo grid hover effect
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

  // Form validation
  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      const password = this.querySelector('input[type="password"]');
      if (password && password.value.length < 6) {
        e.preventDefault();
        alert("Password must be at least 6 characters");
      }
    });
  });
});
