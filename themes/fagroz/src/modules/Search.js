import $ from "jquery";

class Search {
  constructor() {
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.isOverlayOpen = false;
    this.isSpinnerInvisible = false;
    this.typingTimer = null;
    this.previousValue = "";
    this.currentPage = 1;
    this.totalPages = 1;
    this.perPage = Number(universityData?.posts_per_page || 6);
    this.events();
  }

  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keyup", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
    this.resultsDiv.on("click", ".search-pagination__item", (event) => {
      event.preventDefault();
      const page = Number($(event.currentTarget).data("page"));

      if (!page || page === this.currentPage) {
        return;
      }

      this.getResults(page);
    });
  }

  typingLogic() {
    const currentValue = this.searchField.val().trim();

    if (currentValue !== this.previousValue) {
      clearTimeout(this.typingTimer);

      if (currentValue) {
        this.currentPage = 1;
        this.totalPages = 1;

        if (!this.isSpinnerInvisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerInvisible = true;
        }

        this.typingTimer = setTimeout(() => this.getResults(1), 2000);
      } else {
        this.resultsDiv.html("");
        this.isSpinnerInvisible = false;
      }
    }

    this.previousValue = currentValue;
  }

  renderPagination(currentPage, totalPages) {
    if (totalPages <= 1) {
      return "";
    }

    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    if (endPage - startPage < 4) {
      if (startPage === 1) {
        endPage = Math.min(totalPages, startPage + 4);
      } else {
        startPage = Math.max(1, endPage - 4);
      }
    }

    const items = [
      `<a href="#" class="search-pagination__item search-pagination__item--nav ${currentPage <= 1 ? "is-disabled" : ""}" data-page="${Math.max(1, currentPage - 1)}" aria-label="Página anterior">&laquo;</a>`,
    ];

    for (let page = startPage; page <= endPage; page += 1) {
      const isCurrent = page === currentPage ? "current" : "";
      items.push(
        `<a href="#" class="search-pagination__item ${isCurrent}" data-page="${page}" aria-label="Ir para a página ${page}">${page}</a>`,
      );
    }

    items.push(
      `<a href="#" class="search-pagination__item search-pagination__item--nav ${currentPage >= totalPages ? "is-disabled" : ""}" data-page="${Math.min(totalPages, currentPage + 1)}" aria-label="Próxima página">&raquo;</a>`,
    );

    return `
      <nav class="search-pagination" aria-label="Paginação de resultados da busca">
        <div class="search-pagination__nav">
          ${items.join("")}
        </div>
      </nav>
    `;
  }

  getResults(page = 1) {
    const term = this.searchField.val().trim();
    this.currentPage = page;

    $.getJSON(
      `${universityData.root_url}/wp-json/university/v1/search?term=${encodeURIComponent(term)}&page=${page}&per_page=${this.perPage}`,
      (response) => {
        const posts = response.results || [];
        const currentPage = Number(response.current_page || page);
        const totalPages = Number(response.total_pages || 1);
        const totalItems = Number(response.total_items || posts.length);

        if (!posts.length) {
          this.resultsDiv.html(
            '<h2 class="search-title">Nenhuma notícia encontrada.</h2>',
          );
          this.isSpinnerInvisible = false;
          return;
        }

        this.totalPages = totalPages;
        this.currentPage = currentPage;

        this.resultsDiv.html(`
          <div class="search-header">
            <h2 class="search-title">Notícias encontradas</h2>
            <p class="search-count">
              ${totalItems} ${totalItems === 1
            ? "notícia encontrada"
            : "notícias encontradas"
          }
            </p>
          </div>

          <div class="search-results-list">
            ${posts
            .map(
              (item) => `
                  <article class="search-result">
                    <h3 class="search-result__title">
                      <a href="${item.link}" target="_blank" rel="noopener noreferrer">
                        ${item.title}
                      </a>
                    </h3>

                    <div class="search-result__url">
                      ${item.link}
                    </div>

                    <div class="search-result__excerpt">
                      ${item.excerpt || ""}
                    </div>
                  </article>
                `,
            )
            .join("")}
          </div>

          ${this.renderPagination(currentPage, totalPages)}
        `);

        this.isSpinnerInvisible = false;
      },
    ).fail(() => {
      this.resultsDiv.html("<p>Erro ao pesquisar.</p>");
      this.isSpinnerInvisible = false;
    });
  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    $(".header-main").removeClass("menu-open");
    this.searchField.val("");
    this.resultsDiv.html("");
    this.previousValue = "";
    this.isSpinnerInvisible = false;
    this.isOverlayOpen = true;
    this.currentPage = 1;
    this.totalPages = 1;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.searchField.val("");
    this.resultsDiv.html("");
    this.previousValue = "";
    this.isSpinnerInvisible = false;
    this.isOverlayOpen = false;
    this.currentPage = 1;
    this.totalPages = 1;
  }

  keyPressDispatcher(e) {
    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }
}

export default Search;
