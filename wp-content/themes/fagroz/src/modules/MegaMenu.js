export default class MegaMenu {
  constructor() {
    this.header = document.querySelector(".header-main");

    this.menuItems = document.querySelectorAll(
      ".header-main .main-nav .menu > .menu-item-has-children"
    );

    if (!this.menuItems.length) return;

    this.init();
  }

  closeAll() {
    this.menuItems.forEach((item) => {
      item.querySelector(".mega-menu")?.classList.remove("is-open");
    });

    this.header?.classList.remove("mega-open");
  }

  init() {
    this.menuItems.forEach((item) => {
      const megaMenu = item.querySelector(".mega-menu");
      if (!megaMenu) return;

      let timeout;

      const open = () => {
        clearTimeout(timeout);

        this.closeAll();

        megaMenu.classList.add("is-open");
        this.header?.classList.add("mega-open");
      };

      const close = () => {
        timeout = setTimeout(() => {
          megaMenu.classList.remove("is-open");

          const hasOpenMenu = document.querySelector(".mega-menu.is-open");

          if (!hasOpenMenu) {
            this.header?.classList.remove("mega-open");
          }
        }, 250);
      };

      item.addEventListener("mouseenter", open);

      item.addEventListener("mouseleave", (e) => {
        if (megaMenu.contains(e.relatedTarget)) return;
        close();
      });

      megaMenu.addEventListener("mouseenter", () => {
        clearTimeout(timeout);
      });

      megaMenu.addEventListener("mouseleave", (e) => {
        if (item.contains(e.relatedTarget)) return;
        close();
      });
    });
  }
}