import "../css/app.css";
import "../css/header.css";
import "./header";

document.documentElement.classList.add("js");

document.querySelectorAll(".material-symbols-outlined").forEach((el) => {
  if (!el.dataset.icon) {
    el.dataset.icon = el.textContent.trim();
  }
});

// Smooth accordion animation for shop sidebar filters.
document.querySelectorAll(".shop-filter-accordion").forEach((accordion) => {
  const summary = accordion.querySelector("summary");
  const panel = accordion.querySelector(".shop-filter-panel");
  if (!summary || !panel) return;

  const setOpenState = (isOpen) => {
    if (isOpen) {
      accordion.setAttribute("open", "");
      panel.style.maxHeight = `${panel.scrollHeight}px`;
      panel.style.opacity = "1";
      panel.style.transform = "translateY(0)";
    } else {
      panel.style.maxHeight = "0px";
      panel.style.opacity = "0";
      panel.style.transform = "translateY(-4px)";
      window.setTimeout(() => accordion.removeAttribute("open"), 260);
    }
  };

  setOpenState(accordion.hasAttribute("open"));

  summary.addEventListener("click", (event) => {
    event.preventDefault();
    const isOpen = accordion.hasAttribute("open");
    if (isOpen) {
      panel.style.maxHeight = `${panel.scrollHeight}px`;
      requestAnimationFrame(() => setOpenState(false));
    } else {
      accordion.setAttribute("open", "");
      panel.style.maxHeight = "0px";
      panel.style.opacity = "0";
      panel.style.transform = "translateY(-4px)";
      requestAnimationFrame(() => {
        panel.style.maxHeight = `${panel.scrollHeight}px`;
        panel.style.opacity = "1";
        panel.style.transform = "translateY(0)";
      });
    }
  });
});
