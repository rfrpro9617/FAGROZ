(() => {
  const config = window.fagrozDocentesEmail;

  if (!config?.ajaxUrl || !config?.nonce) {
    return;
  }

  document.querySelectorAll(".js-email").forEach((element) => {
    element.addEventListener("click", async (event) => {
      event.preventDefault();

      if (element.getAttribute("aria-busy") === "true") {
        return;
      }

      const nome = element.dataset.nome;
      const label = element.querySelector("[data-email-label]");

      if (!nome || !label) {
        return;
      }

      element.setAttribute("aria-busy", "true");
      label.textContent = "Carregando...";

      try {
        const response = await fetch(config.ajaxUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
          },
          body: new URLSearchParams({
            action: element.dataset.emailAction || "fagroz_get_docente_email",
            nonce: config.nonce,
            nome,
          }),
        });
        const payload = await response.json();

        if (!response.ok || !payload.success || !payload.data?.email) {
          throw new Error("Não foi possível carregar o e-mail.");
        }

        const email = payload.data.email;
        const emailText = document.createElement("span");
        emailText.className = "js-email__value";
        emailText.setAttribute("role", "status");
        emailText.textContent = email;
        element.replaceWith(emailText);
      } catch (error) {
        label.textContent = "Não foi possível carregar o e-mail.";
      } finally {
        element.removeAttribute("aria-busy");
      }
    });
  });
})();
