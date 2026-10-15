export default class ConcluintesCurso {
  constructor() {
    this.containers = document.querySelectorAll("[data-concluintes-curso]");

    this.containers.forEach((container) => {
      this.setupFilters(container);
    });
  }

  setupFilters(container) {
    const nameFilter = container.querySelector("[data-concluintes-curso-nome]");
    const periodFilter = container.querySelector("[data-concluintes-curso-periodo]");
    const items = [...container.querySelectorAll("[data-concluintes-curso-item]")];
    const groups = [...container.querySelectorAll("[data-concluintes-curso-grupo]")];
    const emptyMessage = container.querySelector("[data-concluintes-curso-empty]");

    if (!nameFilter || !periodFilter || !items.length || !emptyMessage) {
      return;
    }

    const applyFilters = () => {
      const nameTerm = this.normalize(nameFilter.value);
      const periodTerm = this.normalize(periodFilter.value);
      let visibleCount = 0;

      items.forEach((item) => {
        const matchesName = this.normalize(item.textContent).includes(nameTerm);
        const matchesPeriod = this.normalize(item.dataset.periodo || "").includes(periodTerm);
        const isVisible = matchesName && matchesPeriod;

        item.hidden = !isVisible;
        visibleCount += isVisible ? 1 : 0;
      });

      groups.forEach((group) => {
        group.hidden = !group.querySelector("[data-concluintes-curso-item]:not([hidden])");
      });

      emptyMessage.hidden = visibleCount > 0;
    };

    nameFilter.addEventListener("input", applyFilters);
    periodFilter.addEventListener("input", applyFilters);
  }

  normalize(value) {
    return value
      .trim()
      .toLocaleLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  }
}
