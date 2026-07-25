document.addEventListener("DOMContentLoaded", () => {
  const toggleSidebar = document.querySelector(".toggle-sidebar");
  const logo = document.querySelector(".logo-box");
  const sidebar = document.querySelector(".sidebar");

  if (toggleSidebar && sidebar) {
    toggleSidebar.addEventListener("click", (e) => {
      // Avoid double-toggle when clicking children handled by app-layout.blade.php
      if (e.target.closest("#hide-toggle") || e.target.closest("#show-toggle")) {
        return;
      }
      sidebar.classList.toggle("close");
    });
  }

  if (logo && sidebar) {
    logo.addEventListener("click", (e) => {
      e.preventDefault();
      sidebar.classList.toggle("close");
    });
  }
});


const inputQty = document.querySelector(".input-qty");
function limitInputLength(inputQty, maxLength) {
  if (inputQty.value.length > maxLength) {
    inputQty.value = inputQty.value.slice(0, maxLength);
  }
};

