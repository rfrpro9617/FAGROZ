export default class SingleTabs {
  constructor() {
    this.tabList = document.querySelector("[data-single-tabs-nav]");

    if (!this.tabList) {
      return;
    }

    this.tabs = [...this.tabList.querySelectorAll('[role="tab"]')];
    this.panels = [...document.querySelectorAll("[data-single-tabs-panel]")];

    this.tabs.forEach((tab, index) => {
      tab.addEventListener("click", () => this.activateTab(index));
      tab.addEventListener("keydown", (event) => this.handleKeydown(event, index));
    });

    this.activateTab(0);
  }

  activateTab(index) {
    this.tabs.forEach((tab, tabIndex) => {
      const isActive = tabIndex === index;
      tab.classList.toggle("is-active", isActive);
      tab.setAttribute("aria-selected", String(isActive));
      tab.setAttribute("tabindex", isActive ? "0" : "-1");
    });

    this.panels.forEach((panel, panelIndex) => {
      panel.hidden = panelIndex !== index;
    });
  }

  handleKeydown(event, index) {
    let nextIndex = index;

    if (event.key === "ArrowDown" || event.key === "ArrowRight") {
      nextIndex = (index + 1) % this.tabs.length;
    } else if (event.key === "ArrowUp" || event.key === "ArrowLeft") {
      nextIndex = (index - 1 + this.tabs.length) % this.tabs.length;
    } else if (event.key === "Home") {
      nextIndex = 0;
    } else if (event.key === "End") {
      nextIndex = this.tabs.length - 1;
    } else {
      return;
    }

    event.preventDefault();
    this.activateTab(nextIndex);
    this.tabs[nextIndex].focus();
  }
}