"use strict";

// Avvia tutte le init quando il DOM è pronto
document.addEventListener("DOMContentLoaded", () => {
  noviInitScrollDragging();
  noviInitScrollTopButton();
  noviInitMenuToggle();
  noviInitAnimations();
  noviInitCustomCursor();
});

/* ==========================================================================
   1) SCROLL DRAGGING CATEGORY (mouse + touch)  —  .category-list
   ========================================================================== */
function noviInitScrollDragging() {
  const slider = document.querySelector(".category-list");
  if (!slider) return;

  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;

  // Mouse
  slider.addEventListener("mousedown", (e) => {
    isDown = true;
    slider.classList.add("dragging");
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });

  slider.addEventListener("mouseleave", () => {
    isDown = false;
    slider.classList.remove("dragging");
  });

  slider.addEventListener("mouseup", () => {
    isDown = false;
    slider.classList.remove("dragging");
  });

  slider.addEventListener("mousemove", (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.5; // fattore velocità
    slider.scrollLeft = scrollLeft - walk;
  });

  // Touch
  slider.addEventListener("touchstart", (e) => {
    startX = e.touches[0].pageX;
    scrollLeft = slider.scrollLeft;
  });

  slider.addEventListener("touchmove", (e) => {
    const x = e.touches[0].pageX;
    const walk = (x - startX) * 1.5;
    slider.scrollLeft = scrollLeft - walk;
  });
}

/* ==========================================================================
   2) SCROLL TO TOP BUTTON — #scrollTop
   ========================================================================== */
function noviInitScrollTopButton() {
  const scrollTopButton = document.getElementById("scrollTop");
  if (!scrollTopButton) return;

  const toggleScrollButton = () => {
    const isVisible = window.scrollY > 100;
    scrollTopButton.classList.toggle("is-visible", isVisible);
    scrollTopButton.setAttribute("aria-hidden", isVisible ? "false" : "true");
  };

  const scrollToTop = (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  scrollTopButton.addEventListener("click", scrollToTop);
  scrollTopButton.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") scrollToTop(e);
  });

  window.addEventListener("scroll", toggleScrollButton, { passive: true });
  toggleScrollButton();
}

/* ==========================================================================
   3) BURGER MENU + Focus Trap + ARIA — #burger-toggle / #header-menu
   ========================================================================== */
function noviInitMenuToggle() {
  const burger = document.getElementById("burger-toggle");
  const nav = document.getElementById("header-menu");
  if (!burger || !nav) return;

  const focusableSelectors =
    'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
  let releaseFocusTrap;

  const trapFocus = (container) => {
    const focusableEls = container.querySelectorAll(focusableSelectors);
    if (!focusableEls.length) return () => {};
    const firstEl = focusableEls[0];
    const lastEl = focusableEls[focusableEls.length - 1];

    const handleTab = (e) => {
      if (e.key !== "Tab") return;
      if (e.shiftKey) {
        if (document.activeElement === firstEl) {
          e.preventDefault();
          lastEl.focus();
        }
      } else {
        if (document.activeElement === lastEl) {
          e.preventDefault();
          firstEl.focus();
        }
      }
    };

    container.addEventListener("keydown", handleTab);
    return () => container.removeEventListener("keydown", handleTab);
  };

  const handleKeyboardNavigation = (e) => {
    const focusableEls = nav.querySelectorAll(focusableSelectors);
    if (!focusableEls.length) return;

    const activeIndex = Array.from(focusableEls).indexOf(
      document.activeElement
    );

    if (e.key === "Escape") toggleMenu();
    if (e.key === "ArrowDown") {
      e.preventDefault();
      const nextIndex = (activeIndex + 1) % focusableEls.length;
      focusableEls[nextIndex].focus();
    }
    if (e.key === "ArrowUp") {
      e.preventDefault();
      const prevIndex =
        (activeIndex - 1 + focusableEls.length) % focusableEls.length;
      focusableEls[prevIndex].focus();
    }
  };

  const toggleMenu = () => {
    const isOpen = nav.classList.contains("is-open");

    if (!isOpen) {
      // Open
      nav.classList.remove("closing");
      nav.classList.add("opening", "is-open", "active");
      document.body.classList.add("no-scroll");
      nav.setAttribute("aria-expanded", "true");
      nav.setAttribute("aria-hidden", "false");
      burger.classList.add("open");

      releaseFocusTrap = trapFocus(nav);

      setTimeout(() => {
        const firstLink = nav.querySelector("a[href]");
        if (firstLink) firstLink.focus();
        document.addEventListener("keydown", handleKeyboardNavigation);
      }, 300);

      const handleOpen = () => {
        nav.classList.remove("opening");
        nav.removeEventListener("animationend", handleOpen);
      };
      nav.addEventListener("animationend", handleOpen);
    } else {
      // Close
      nav.classList.remove("opening", "active", "is-open");
      document.body.classList.remove("no-scroll");
      nav.classList.add("closing");

      if (releaseFocusTrap) releaseFocusTrap();
      burger.focus();
      document.removeEventListener("keydown", handleKeyboardNavigation);

      nav.setAttribute("aria-expanded", "false");
      nav.setAttribute("aria-hidden", "true");
      burger.classList.remove("open");

      const handleClose = () => {
        nav.classList.remove("closing");
        nav.removeEventListener("animationend", handleClose);
      };
      nav.addEventListener("animationend", handleClose);
    }
  };

  burger.addEventListener("click", toggleMenu);
  burger.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      toggleMenu();
    }
  });
}

/* ==========================================================================
   4) ANIMATIONS ON VIEWPORT — .animation ➜ .entry-page
   ========================================================================== */
function noviInitAnimations() {
  const elementsToWatch = document.querySelectorAll(".animation");
  if (!elementsToWatch.length) return;

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        requestAnimationFrame(() => entry.target.classList.add("entry-page"));
        obs.unobserve(entry.target);
      });
    },
    { threshold: 0.1, rootMargin: "0px 0px -10% 0px" }
  );

  elementsToWatch.forEach((el) => observer.observe(el));
}

/* ==========================================================================
   5) CUSTOM CURSOR — .custom-cursor (disabilitato se body.logged-in)
   ========================================================================== */
function noviInitCustomCursor() {
  if (document.body.classList.contains("logged-in")) return;
  const cursor = document.querySelector(".custom-cursor");
  if (!cursor) return;

  let mouseX = 0,
    mouseY = 0;
  let currentX = 0,
    currentY = 0;
  const speed = 0.2;

  document.addEventListener(
    "mousemove",
    (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursor.style.opacity = 1;
    },
    { passive: true }
  );

  document.addEventListener("mouseleave", () => {
    cursor.style.opacity = 0;
  });
  document.addEventListener("mouseenter", () => {
    cursor.style.opacity = 1;
  });

  const animateCursor = () => {
    currentX += (mouseX - currentX) * speed;
    currentY += (mouseY - currentY) * speed;
    cursor.style.transform = `translate(${currentX}px, ${currentY}px)`;
    requestAnimationFrame(animateCursor);
  };
  animateCursor();

  const interactiveSel =
    "a, button, input, textarea, select, [tabindex]:not([tabindex='-1']), .hover-target";

  document.body.addEventListener("mouseover", (e) => {
    if (e.target.closest(interactiveSel)) cursor.classList.add("cursor-hover");
  });
  document.body.addEventListener("mouseout", (e) => {
    if (e.target.closest(interactiveSel))
      cursor.classList.remove("cursor-hover");
  });
}
