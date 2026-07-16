(function () {
  var sidebar = document.querySelector(".sidebar");
  var listItems = document.querySelectorAll(".sidebar-list li");
  var toggleSidebar = document.querySelector(".toggle-sidebar");
  var logo = document.querySelector(".logo-box");
  var mobileToggleBtn = document.getElementById("mobile-sidebar-toggle");
  var overlay = document.getElementById("sidebar-overlay");

  // -- Dropdown items
  listItems.forEach(function (item) {
    item.addEventListener("click", function () {
      var isActive = item.classList.contains("active");
      listItems.forEach(function (el) { el.classList.remove("active"); });
      if (!isActive) item.classList.add("active");
    });
  });

  // -- Desktop: toggle via icon button or logo click
  function isDesktop() {
    return window.innerWidth > 774;
  }

  if (toggleSidebar) {
    toggleSidebar.addEventListener("click", function () {
      if (isDesktop()) sidebar.classList.toggle("close");
    });
  }

  if (logo) {
    logo.addEventListener("click", function () {
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
    mobileToggleBtn.addEventListener("click", function () {
      if (sidebar.classList.contains("mobile-open")) {
        closeMobileSidebar();
      } else {
        openMobileSidebar();
      }
    });
  }

  if (overlay) {
    overlay.addEventListener("click", closeMobileSidebar);
  }

  // Tutup sidebar mobile saat resize ke desktop
  window.addEventListener("resize", function () {
    if (isDesktop()) closeMobileSidebar();
  });
})();
