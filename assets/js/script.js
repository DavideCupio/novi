document.addEventListener("DOMContentLoaded", function () {
  initCustomCursor();
  initScrollDragging();
  initScrollTopButton();
  initMenuToggle();
  initAnimations();
});

/** GRAB E TOUCH SCROLL CATEGORY **/
function initScrollDragging() {
  const slider = document.querySelector(".category-list");
  if (!slider) return;

  let isDown = false;
  let startX;
  let scrollLeft;

  // Mouse events
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
    const walk = (x - startX) * 1.5;
    slider.scrollLeft = scrollLeft - walk;
  });

  // Touch events
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

/** SCROLL TO TOP BUTTON **/
function initScrollTopButton() {
  const scrollTopButton = document.getElementById("scrollTop");
  if (!scrollTopButton) return;

  function toggleScrollButton() {
    const isVisible = window.scrollY > 100;
    scrollTopButton.classList.toggle("is-visible", isVisible);
    scrollTopButton.setAttribute("aria-hidden", isVisible ? "false" : "true");
  }

  function scrollToTop(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  scrollTopButton.addEventListener("click", scrollToTop);
  scrollTopButton.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      scrollToTop(e);
    }
  });

  window.addEventListener("scroll", toggleScrollButton);
  toggleScrollButton();
}

/** BURGER MENU & FOCUS TRAP **/
function initMenuToggle() {
  const burger = document.getElementById("burger-toggle");
  const nav = document.getElementById("header-menu");
  if (!burger || !nav) return;

  const focusableSelectors =
    'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
  let releaseFocusTrap;

  function trapFocus(container) {
    const focusableEls = container.querySelectorAll(focusableSelectors);
    const firstEl = focusableEls[0];
    const lastEl = focusableEls[focusableEls.length - 1];

    function handleTab(e) {
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
    }

    container.addEventListener("keydown", handleTab);
    return () => container.removeEventListener("keydown", handleTab);
  }

  function handleKeyboardNavigation(e) {
    const focusableEls = nav.querySelectorAll(focusableSelectors);
    const activeIndex = Array.from(focusableEls).indexOf(
      document.activeElement
    );

    if (e.key === "Escape") {
      toggleMenu();
    }

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
  }

  function toggleMenu() {
    const isOpen = nav.classList.contains("is-open");

    if (!isOpen) {
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

      nav.addEventListener("animationend", function handleOpen() {
        nav.classList.remove("opening");
        nav.removeEventListener("animationend", handleOpen);
      });
    } else {
      nav.classList.remove("opening", "active", "is-open");
      document.body.classList.remove("no-scroll");
      nav.classList.add("closing");

      if (releaseFocusTrap) releaseFocusTrap();
      burger.focus();
      document.removeEventListener("keydown", handleKeyboardNavigation);

      nav.setAttribute("aria-expanded", "false");
      nav.setAttribute("aria-hidden", "true");
      burger.classList.remove("open");

      nav.addEventListener("animationend", function handleClose() {
        nav.classList.remove("closing");
        nav.removeEventListener("animationend", handleClose);
      });
    }
  }

  burger.addEventListener("click", toggleMenu);
  burger.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      toggleMenu();
    }
  });
}

/** ANIMATION OBSERVER **/
function initAnimations() {
  const elementsToWatch = document.querySelectorAll(".animation");
  if (!elementsToWatch.length) return;

  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -10% 0px",
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        requestAnimationFrame(() => {
          entry.target.classList.add("entry-page");
        });
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  elementsToWatch.forEach((element) => observer.observe(element));
}
/** CUSTOM CURSOR **/
function initCustomCursor() {
  if (document.body.classList.contains("logged-in")) return;
  const cursor = document.querySelector(".custom-cursor");
  if (!cursor) return;

  let mouseX = 0;
  let mouseY = 0;
  let currentX = 0;
  let currentY = 0;
  const speed = 0.2; // Più basso = più lento e fluido

  document.addEventListener("mousemove", (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    cursor.style.opacity = 1;
  });

  document.addEventListener("mouseleave", () => {
    cursor.style.opacity = 0;
  });

  document.addEventListener("mouseenter", () => {
    cursor.style.opacity = 1;
  });

  function animateCursor() {
    currentX += (mouseX - currentX) * speed;
    currentY += (mouseY - currentY) * speed;
    cursor.style.transform = `translate(${currentX}px, ${currentY}px)`;
    requestAnimationFrame(animateCursor);
  }

  animateCursor();
  bindCursorHoverEffects();
}

function bindCursorHoverEffects() {
  const cursor = document.querySelector(".custom-cursor");
  if (!cursor) return;

  document.body.addEventListener("mouseover", (e) => {
    const target = e.target.closest(
      "a, button, input, textarea, select, [tabindex]:not([tabindex='-1']), .hover-target"
    );
    if (target) {
      cursor.classList.add("cursor-hover");
    }
  });

  document.body.addEventListener("mouseout", (e) => {
    const target = e.target.closest(
      "a, button, input, textarea, select, [tabindex]:not([tabindex='-1']), .hover-target"
    );
    if (target) {
      cursor.classList.remove("cursor-hover");
    }
  });
}
