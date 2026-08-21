<?php
$where_we_are_page = get_page_by_path('pagina-configuracao-onde-estamos');
$acfAddress = get_field('address', $where_we_are_page->ID);
$bg_photo = $acfAddress['bg_photo'] ?? '';
if (is_array($bg_photo)) {
  $bg_photo = $bg_photo['url'] ?? '';
}

$map_url = 'https://www.google.com/maps/search/?api=1&query=Av.+Bento+Gonçalves,+7712,+Porto+Alegre,+RS';
$address = 'Av. Bento Gonçalves, 7712 - Agronomia,<br>Porto Alegre - RS, 91540-000';
?>
<section id="onde-estamos" class="where-we-are section">
  <div class="where-we-are__image">
    <img
      src="<?php echo esc_url($bg_photo); ?>"
      alt="Mapa da localização da FAGROZ"
      loading="lazy">
  </div>

  <div class="where-we-are__overlay"></div>

  <div class="container where-we-are__inner">
    <div class="where-we-are__content">
      <h2 class="where-we-are__title">
        Onde<br>estamos?
      </h2>
      <p class="where-we-are__address">
        <?php echo wp_kses_post($address); ?>
      </p>
    </div>
  </div>

  <a
    class="where-we-are__button"
    href="<?php echo esc_url($map_url); ?>"
    target="_blank"
    rel="noopener noreferrer">
    <span class="dashicons dashicons-external" aria-hidden="true"></span>
    Abra no maps
  </a>
</section>