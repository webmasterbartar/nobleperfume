const header = document.querySelector(".noble-header");
if (header) {
  const drawer = header.querySelector(".noble-drawer");
  const backdrop = header.querySelector(".noble-drawer-backdrop");
  const drawerToggle = header.querySelector(".noble-header__drawer-toggle");
  const drawerClose = header.querySelector("[data-drawer-close]");
  const searchToggle = header.querySelector("[data-search-toggle]");
  const searchWrap = header.querySelector(".noble-header__search");
  const searchInput = header.querySelector("#noble-header-search");
  const megaTriggers = Array.from(header.querySelectorAll("[data-mega-target]"));
  const megaMenus = Array.from(header.querySelectorAll("[data-mega-menu]"));
  let lastFocused = null;
  let searchPanelEndHandler = null;

  const searchTransitionCleanup = () => {
    if (searchPanelEndHandler) {
      searchWrap.removeEventListener("transitionend", searchPanelEndHandler);
      searchPanelEndHandler = null;
    }
  };

  const openSearchPanel = () => {
    searchTransitionCleanup();
    searchWrap.hidden = false;
    searchToggle?.setAttribute("aria-expanded", "true");
    requestAnimationFrame(() => searchWrap.classList.add("is-open"));
    searchInput?.focus();
  };

  const closeSearchPanel = () => {
    if (!searchWrap.classList.contains("is-open")) return;
    searchWrap.classList.remove("is-open");
    searchToggle?.setAttribute("aria-expanded", "false");
    searchTransitionCleanup();
    searchPanelEndHandler = (e) => {
      if (e.target !== searchWrap || e.propertyName !== "max-height") return;
      searchWrap.hidden = true;
      searchTransitionCleanup();
    };
    searchWrap.addEventListener("transitionend", searchPanelEndHandler);
  };

  const toggleDrawer = (open) => {
    if (!drawer || !backdrop) return;
    if (open) {
      drawer.hidden = false;
      backdrop.hidden = false;
    }
    requestAnimationFrame(() => {
      drawer.classList.toggle("is-open", open);
      backdrop.classList.toggle("is-open", open);
    });
    document.body.classList.toggle("noble-lock", open);
    drawerToggle?.setAttribute("aria-expanded", open ? "true" : "false");
    if (open) {
      lastFocused = document.activeElement;
      drawer.querySelector("a,button,input")?.focus();
    } else {
      lastFocused?.focus();
    }
    if (!open) {
      const done = () => {
        drawer.hidden = true;
        backdrop.hidden = true;
        drawer.removeEventListener("transitionend", done);
      };
      drawer.addEventListener("transitionend", done);
    }
  };

  drawerToggle?.addEventListener("click", () => toggleDrawer(true));
  drawerClose?.addEventListener("click", () => toggleDrawer(false));
  backdrop?.addEventListener("click", () => toggleDrawer(false));

  searchToggle?.addEventListener("click", () => {
    if (searchWrap.classList.contains("is-open")) {
      closeSearchPanel();
    } else {
      openSearchPanel();
    }
  });

  const closeMega = () => {
    megaMenus.forEach((menu) => menu.classList.remove("is-open"));
    megaTriggers.forEach((btn) => btn.setAttribute("aria-expanded", "false"));
  };
  const openMega = (key) => {
    closeMega();
    const trigger = header.querySelector(`[data-mega-target="${key}"]`);
    const panel = header.querySelector(`[data-mega-menu="${key}"]`);
    if (trigger && panel) {
      trigger.setAttribute("aria-expanded", "true");
      panel.classList.add("is-open");
    }
  };

  megaTriggers.forEach((trigger) => {
    const key = trigger.getAttribute("data-mega-target");
    trigger.addEventListener("mouseenter", () => openMega(key));
    trigger.addEventListener("focus", () => openMega(key));
    trigger.addEventListener("keydown", (e) => {
      if (e.key === "Enter") openMega(key);
      if (e.key === "Escape") closeMega();
    });
  });
  megaMenus.forEach((menu) => {
    menu.addEventListener("mouseenter", () => {
      const key = menu.getAttribute("data-mega-menu");
      if (key) openMega(key);
    });
  });
  header.querySelector(".noble-header__nav")?.addEventListener("mouseleave", closeMega);
  header.querySelector(".noble-header__nav")?.addEventListener("focusout", (e) => {
    if (!header.querySelector(".noble-header__nav")?.contains(e.relatedTarget)) {
      closeMega();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      if (searchWrap.classList.contains("is-open")) {
        closeSearchPanel();
      }
      closeMega();
      toggleDrawer(false);
    }
    if (e.key === "Tab" && drawer && !drawer.hidden) {
      const focusables = drawer.querySelectorAll("a,button,input,[tabindex]:not([tabindex='-1'])");
      if (!focusables.length) return;
      const first = focusables[0];
      const last = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  header.querySelectorAll(".noble-mobile-nav .menu-item-has-children").forEach((item) => {
    const link = item.querySelector(":scope > a");
    const sub = item.querySelector(":scope > .sub-menu");
    if (!link || !sub) return;
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "noble-accordion-toggle";
    btn.innerHTML = `${link.textContent}<span aria-hidden="true">▾</span>`;
    item.insertBefore(btn, link);
    link.remove();
    btn.addEventListener("click", () => {
      const open = item.classList.toggle("is-active");
      sub.style.maxHeight = open ? `${sub.scrollHeight}px` : "0px";
    });
  });

  window.addEventListener("scroll", () => {
    header.classList.toggle("is-sticky", window.scrollY > 60);
  });
}
