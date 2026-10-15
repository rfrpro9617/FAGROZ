<?php
$title = $args['title'] ?? '';
$content = $args['content'] ?? '';
?>
<section class="members">
  <div class="container">
    <div class="members__content">
      <header class="members__header">
        <h2 class="members__title"><?php echo esc_html($title); ?></h2>
        <p class="members__description">
          <?php echo esc_html($content); ?>
        </p>
      </header>
      <nav class="members__navigation" aria-label="Composição da FAGROZ">
        <ul class="members__list">
          <li id="direcao" class="members__item">
            <a class="members__link" href="<?php echo site_url('/direcao') ?>">
              <span class="members__link-text">Nossa direção</span>
              <span class="members__link-icon dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
            </a>
          </li>
          <li id="docentes" class="members__item">
            <a class="members__link" href="<?php echo site_url('/docentes') ?>">
              <span class="members__link-text">Docentes</span>
              <span class="members__link-icon dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
            </a>
          </li>
          <li id="servidores" class="members__item">
            <a class="members__link" href="<?php echo site_url('/servidores') ?>">
              <span class="members__link-text">Técnicos Administrativos</span>
              <span class="members__link-icon dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</section>