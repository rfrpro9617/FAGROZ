export default class MobileMenu {
  constructor() {
    this.header = document.querySelector(".header-main");
    this.menuButton = document.querySelector(".menu-toggle");

    if (!this.header || !this.menuButton) return;

    this.events();
  }

  events() {
    this.menuButton.addEventListener("click", () => this.toggleMenu());
  }

  toggleMenu() {
    this.header.classList.toggle("menu-open");
  }
}