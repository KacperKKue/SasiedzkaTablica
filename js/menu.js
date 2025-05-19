document.addEventListener("DOMContentLoaded", function () {
  const avatar = document.getElementById("avatar");
  const dropdown = document.getElementById("userDropdown");

  avatar.addEventListener("click", function (e) {
    e.stopPropagation();
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  });

  // Zamknij dropdown po kliknięciu poza nim
  document.addEventListener("click", function () {
    dropdown.style.display = "none";
  });
});
