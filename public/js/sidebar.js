document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const listItems = document.querySelectorAll(".sidebar-list li");
  const toggleSidebar = document.querySelector(".toggle-sidebar");
  const logo = document.querySelector(".logo-box");
  const mobileToggleBtn = document.getElementById("mobile-sidebar-toggle");
  const overlay = document.getElementById("sidebar-overlay");

  // -- Dropdown items
  listItems.forEach((item) => {
    item.addEventListener("click", () => {
      const isActive = item.classList.contains("active");
      listItems.forEach((el) => el.classList.remove("active"));
      if (!isActive) item.classList.add("active");
    });
  });

  // -- Desktop: toggle via icon button or logo click
  function isDesktop() {
    return window.innerWidth > 774;
  }

  if (toggleSidebar) {
    toggleSidebar.addEventListener("click", () => {
      if (isDesktop()) sidebar.classList.toggle("close");
    });
  }

  if (logo) {
    logo.addEventListener("click", () => {
      if (isDesktop()) sidebar.classList.toggle("close");
    });
  }

  // -- Mobile: open / close sidebar
  function openMobileSidebar() {
    sidebar.classList.add("mobile-open");
    if (overlay) overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeMobileSidebar() {
    sidebar.classList.remove("mobile-open");
    if (overlay) overlay.classList.remove("active");
    document.body.style.overflow = "";
  }

  if (mobileToggleBtn) {
    mobileToggleBtn.addEventListener("click", () => {
      sidebar.classList.contains("mobile-open")
        ? closeMobileSidebar()
        : openMobileSidebar();
    });
  }

  if (overlay) {
    overlay.addEventListener("click", closeMobileSidebar);
  }

  // Tutup sidebar mobile saat resize ke desktop
  window.addEventListener("resize", () => {
    if (isDesktop()) closeMobileSidebar();
  });
});
