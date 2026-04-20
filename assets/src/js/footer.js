document.querySelectorAll(".noble-footer [data-footer-accordion]").forEach((block) => {
  const btn = block.querySelector(".noble-footer__block-toggle");
  if (!btn) return;

  btn.addEventListener("click", () => {
    const open = !block.classList.contains("is-open");
    block.classList.toggle("is-open", open);
    btn.setAttribute("aria-expanded", open ? "true" : "false");
  });
});

const resetFooterAccordionsDesktop = () => {
  if (!window.matchMedia("(min-width: 992px)").matches) return;
  document.querySelectorAll(".noble-footer [data-footer-accordion]").forEach((b) => {
    b.classList.remove("is-open");
    b.querySelector(".noble-footer__block-toggle")?.setAttribute("aria-expanded", "false");
  });
};

window.addEventListener("resize", resetFooterAccordionsDesktop);
