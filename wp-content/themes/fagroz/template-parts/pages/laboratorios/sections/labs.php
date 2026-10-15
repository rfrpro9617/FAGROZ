<?php
$programs = isset($programs) && is_array($programs) ? $programs : [];

if (empty($programs)) {
  $laboratorios = get_posts([
    'post_type'      => 'laboratorios',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
  ]);

  foreach ($laboratorios as $laboratorio) {
    $link = get_permalink($laboratorio->ID);
    $external_link = get_field('external_link', $laboratorio->ID);

    if (is_array($external_link) && !empty($external_link['url'])) {
      $link = $external_link['url'];
    } elseif (is_string($external_link) && !empty($external_link)) {
      $link = $external_link;
    }

    $programs[] = [
      'title'    => get_the_title($laboratorio->ID),
      'bg_photo' => get_the_post_thumbnail_url($laboratorio->ID, 'large'),
      'link'     => $link,
      'id'       => $laboratorio->post_name,
    ];
  }
}

$programs_per_page = 4;
$current_page = max(1, absint($_GET['group_page'] ?? 1));
$total_pages = max(1, (int) ceil(count($programs) / $programs_per_page));
$current_page = min($current_page, $total_pages);

$start_index = ($current_page - 1) * $programs_per_page;
$visible_programs = array_slice($programs, $start_index, $programs_per_page);

$pagination_base = remove_query_arg('group_page', get_permalink(get_the_ID()));
$pagination_base = add_query_arg('group_page', '%#%', $pagination_base);

$pagination = $total_pages > 1 ? paginate_links([
  'base'      => $pagination_base,
  'format'    => '',
  'current'   => $current_page,
  'total'     => $total_pages,
  'type'      => 'list',
  'prev_text' => __('&laquo;'),
  'next_text' => __('&raquo;'),
]) : '';
?>

<section class="research-section research-section--programs">
  <div class="container">
    <div class="research-programs__grid">
      <?php foreach ($visible_programs as $program) : ?>
        <?php
        $card_title = $program['title'] ?? '';
        $card_image = $program['bg_photo'] ?? '';
        $card_link = !empty($program['link'])
          ? $program['link']
          : site_url('/laboratorios');
        ?>

        <article
          id="<?php echo esc_attr($program['id'] ?? ''); ?>"
          class="research-card">
          <div class="research-card__image">
            <?php if ($card_image) : ?>
              <img
                src="<?php echo esc_url($card_image); ?>"
                alt="<?php echo esc_attr($card_title); ?>">
            <?php endif; ?>
          </div>

          <div class="research-card__body">
            <h3 class="research-card__title">
              <?php echo esc_html($card_title); ?>
            </h3>

            <a
              href="<?php echo esc_url($card_link); ?>"
              class="btn-research"
              rel="noopener noreferrer">
              <span class="dashicons dashicons-external"></span>
              Mais informações
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($pagination)) : ?>
      <div class="home-posts__pagination">
        <?php echo wp_kses_post($pagination); ?>
      </div>
    <?php endif; ?>
  </div>
</section>