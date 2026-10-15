<?php
$title = $args['title'];
$description = $args['description'];
$button_title = $args['button_title'] ?? '';
$show_button = $args['show_button'] ?? true;
?>

<section class="great-college-opportunities">
  <div class="container">
    <div class="great-college-opportunities__content">
      <h2 class="great-college-opportunities__heading">
        <?php echo esc_html($title); ?>
      </h2>
      <div class="great-college-opportunities__body">
        <p class="great-college-opportunities__description">
          <?php echo wp_kses_post($description); ?>
        </p>
        <?php if ($show_button) : ?>
          <a href="<?php echo esc_url(site_url('/educacao')); ?>" class="great-college-opportunities__link">
            <span class="great-college-opportunities__link-icon dashicons dashicons-external"></span>
            <span class="great-college-opportunities__link-text">
              <?php echo esc_html($button_title); ?>
            </span>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>