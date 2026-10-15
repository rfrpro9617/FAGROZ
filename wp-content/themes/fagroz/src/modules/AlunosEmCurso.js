export default class AlunosEmCurso {
  constructor() {
    this.containers = document.querySelectorAll("[data-alunos-em-curso]");

    this.containers.forEach((container) => {
      this.setupStudentFilter(container);
    });
  }

  setupStudentFilter(container) {
    const filter = container.querySelector("[data-alunos-em-curso-filter]");
    const names = [...container.querySelectorAll("[data-alunos-em-curso-name]")];
    const emptyMessage = container.querySelector("[data-alunos-em-curso-empty]");

    if (!filter || !names.length || !emptyMessage) {
      return;
    }

    filter.addEventListener("input", () => {
      const term = this.normalize(filter.value);
      let visibleCount = 0;

      names.forEach((name) => {
        const matches = this.normalize(name.textContent).includes(term);
        name.hidden = !matches;

        if (matches) {
          visibleCount += 1;
        }
      });

      emptyMessage.hidden = visibleCount > 0;
    });
  }

  normalize(value) {
    return value
      .trim()
      .toLocaleLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  }
}
