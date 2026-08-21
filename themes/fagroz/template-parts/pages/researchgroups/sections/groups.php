<?php
$research_groups = [
  [
    'slug' => 'abelhas-e-polinizacao',
    'id' => 'abelhas-e-polinizacao',
  ],
  [
    'slug' => 'agrometeorologia',
    'id' => 'agrometeorologia',
  ],
  [
    'slug' => 'aquam',
    'id' => 'aquam',
  ],
  [
    'slug' => 'aviario-de-ensino-e-pesquisa',
    'id' => 'aviario-de-ensino-e-pesquisa',
  ],
  [
    'slug' => 'fisiologia-pos-colheita',
    'id' => 'fisiologia-pos-colheita',
  ],
  [
    'slug' => 'borbulheira-de-citros',
    'id' => 'borbulheira-de-citros',
  ],
  [
    'slug' => 'caracterizacao-de-germoplasma',
    'id' => 'caracterizacao-de-germoplasma',
  ],
  [
    'slug' => 'cepov',
    'id' => 'cepov',
  ],
  [
    'slug' => 'controle-biologico-de-pragas-de-plantas-cultivadas',
    'id' => 'controle-biologico-de-pragas-de-plantas-cultivadas',
  ],
  [
    'slug' => 'grupex',
    'id' => 'grupex',
  ],
  [
    'slug' => 'gpep',
    'id' => 'gpep',
  ],
  [
    'slug' => 'gsr',
    'id' => 'gsr',
  ],
  [
    'slug' => 'herbologia',
    'id' => 'herbologia',
  ],
  [
    'slug' => 'irgeb',
    'id' => 'irgeb',
  ],
  [
    'slug' => 'lezo',
    'id' => 'lezo',
  ],
  [
    'slug' => 'nespro',
    'id' => 'nespro',
  ],
  [
    'slug' => 'nuplac',
    'id' => 'nuplac',
  ],
  [
    'slug' => 'substrato',
    'id' => 'substrato',
  ],
  [
    'slug' => 'uso-de-sensoriamento-remoto-na-sanidade-vegetal',
    'id' => 'uso-de-sensoriamento-remoto-na-sanidade-vegetal',
  ],
];

$programs = isset($programs) && is_array($programs) ? $programs : [];

if (empty($programs)) {
  $programs = [];

  foreach ($research_groups as $group) {
    $page = get_page_by_path($group['slug'], OBJECT, 'research-group');

    if (!$page) {
      continue;
    }

    $link = get_permalink($page->ID);
    $external_link = get_field('external_link', $page->ID);

    if (is_array($external_link) && !empty($external_link['url'])) {
      $link = $external_link['url'];
    } elseif (is_string($external_link) && !empty($external_link)) {
      $link = $external_link;
    }

    $programs[] = [
      'title' => get_the_title($page->ID),
      'bg_photo' => get_the_post_thumbnail_url($page->ID, 'large'),
      'link' => $link,
      'id' => $group['id'],
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
  'base' => $pagination_base,
  'format' => '',
  'current' => $current_page,
  'total' => $total_pages,
  'type' => 'list',
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
        $card_link = !empty($program['link']) ? $program['link'] : site_url('/pos-graduacao');
        ?>
        <article id="<?php echo esc_attr($program['id'] ?? ''); ?>" class="research-card">
          <div class="research-card__image">
            <?php if ($card_image) : ?>
              <img src="<?php echo esc_url($card_image); ?>" alt="<?php echo esc_attr($card_title); ?>">
            <?php endif; ?>
          </div>
          <div class="research-card__body">
            <h3 class="research-card__title"><?php echo esc_html($card_title); ?></h3>
            <a href="<?php echo esc_url($card_link); ?>" class="btn-research">
              <span class="dashicons dashicons-external"></span> Mais informações
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