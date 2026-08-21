<?php
$title = $args['title'] ?? '';
$placeholder = $args['placeholder'] ?? '';
?>
<section class="section search-hero">
  <div class="container">
    <div class="search-hero__content">
        <h1 class="search-hero__title">
          <?php echo esc_html($title); ?>
        </h1>
        <form
          class="search-hero__form"
          role="search"
          method="get"
          action="<?php echo esc_url(home_url('/')); ?>">
          <div class="search-hero__wrapper">
            <span class="search-hero__icon dashicons dashicons-search" aria-hidden="true"></span>
            <label class="search-hero__label" for="search-input">
              <span class="screen-reader-text">
                Pesquisar no site
              </span>
            </label>
            <input
              id="search-input"
              class="search-hero__input"
              type="search"
              name="s"
              value="<?php echo esc_attr(get_search_query()); ?>"
              placeholder="<?php echo esc_attr($placeholder); ?>">
            <button
              class="search-hero__button"
              type="submit"
              aria-label="Pesquisar">
              <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
            </button>
          </div>
        </form>
    </div>
  </div>
</section>