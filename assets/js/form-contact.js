
(function () {
  function applyToField(field) {
    if (!field || field.dataset.placeholderApplied === "true") return;

    const label = field.querySelector(".bricks-form__label");
    const control = field.querySelector(".bricks-form__input, input, select, textarea");

    if (!label || !control) return;

    const labelText = label.textContent.trim();
    if (!labelText) return;

    field.dataset.placeholderApplied = "true";

    label.style.display = "none";
    control.setAttribute("aria-label", labelText);

    if (!control.matches("select")) {
      control.setAttribute("placeholder", labelText);
      return;
    }

    let first = control.querySelector("option");

    if (!first) {
      first = document.createElement("option");
      control.insertBefore(first, control.firstChild);
    }

    first.textContent = labelText;
    first.value = "";
    first.disabled = true;

    if (!control.value) {
      first.selected = true;
    }

    const toggle = () => {
      control.classList.toggle("is-placeholder", !control.value);
    };

    toggle();
    control.addEventListener("change", toggle);
    control.addEventListener("input", toggle);
  }

  function applyAll(container = document) {
    container.querySelectorAll(".bricks-form__field").forEach(applyToField);
  }

  function init() {
    applyAll();

    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
          if (node.nodeType !== 1) return;

          if (node.matches(".bricks-form__field")) {
            applyToField(node);
          } else {
            applyAll(node);
          }
        });
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
