<?php
get_header();

while (have_posts()) : the_post();
  $post_id = get_the_ID();

  $trumbnail = get_field('thumbnail', $post_id);
  $title = get_the_title($post_id);
  $photo = '';

  if (is_array($trumbnail)) {
    $title = $trumbnail['title'] ?? $title;
    $photo = $trumbnail['photo'] ?? '';

    if (is_array($photo)) {
      $photo = $photo['url'] ?? '';
    }
  }

  if (empty($photo)) {
    $photo = get_the_post_thumbnail_url($post_id, 'full');
  }

  get_template_part(
    'template-parts/components/hero/post-hero-image',
    null,
    [
      'title' => $title,
      'image' => $photo,
    ]
  );

  $educationPage = get_page_by_path('educacao');
  $graduationPage = get_page_by_path('graduacao');
  $educationUrl = $educationPage ? get_permalink($educationPage->ID) : site_url('/educacao');
  $graduationUrl = $graduationPage ? get_permalink($graduationPage->ID) : site_url('/graduacao');

  $internalNavigation = get_field('internal_navigation', $post_id);
  $link_1 = is_array($internalNavigation) ? ($internalNavigation['title_link_1'] ?? '') : '';
  $link_2 = is_array($internalNavigation) ? ($internalNavigation['title_link_2'] ?? '') : '';
  $link_3 = is_array($internalNavigation) ? ($internalNavigation['title_link_3'] ?? '') : '';
  $link_4 = is_array($internalNavigation) ? ($internalNavigation['title_link_4'] ?? '') : '';

  $introduction = get_field('introduction_section', $post_id);

  $graduationCurriculum = get_field('graduation_curriculum', $post_id);
  $curriculum = '';

  if (!empty($graduationCurriculum['file'])) {
    $curriculum = is_array($graduationCurriculum['file'])
      ? ($graduationCurriculum['file']['url'] ?? '')
      : $graduationCurriculum['file'];
  } elseif (!empty($graduationCurriculum['link'])) {
    $curriculum = is_array($graduationCurriculum['link'])
      ? ($graduationCurriculum['link']['url'] ?? '')
      : $graduationCurriculum['link'];
  }

  $graduateProfile = get_field('graduate_profile', $post_id);

  $highlights = fagroz_get_graduation_highlights($post_id);

  $internship = get_field('internship_section', $post_id);

  $contact = get_field('contact_section', $post_id);
?>
  <div class="container container--single page-section">
    <?php get_template_part(
      'template-parts/components/breadcrumbs',
      null,
      [
        'items' => [
          [
            'label' => 'Educação',
            'url' => $educationUrl,
          ],
          [
            'label' => 'Graduação',
            'url' => $graduationUrl,
          ],
          [
            'label' => get_the_title(),
            'url' => '',
          ],
        ],
      ]
    ); ?>
  </div>
  <section class="page-graduation">
    <div class="container page-graduation__layout">
      <aside class="page-graduation__sidebar">
        <nav class="page-graduation__nav" aria-label="Nesta Página">
          <ul>
            <li>
              <a href="#sobre">
                <?= esc_html($link_1); ?>
              </a>
            </li>
            <li>
              <a href="#destaques">
                <?= esc_html($link_2); ?>
              </a>
            </li>
            <li>
              <a href="#estagios">
                <?= esc_html($link_3); ?>
              </a>
            </li>
            <li>
              <a href="#contato">
                <?= esc_html($link_4); ?>
              </a>
            </li>
          </ul>
        </nav>
      </aside>
      <main class="page-graduation__main">
        <header id="sobre" class="page-graduation__intro">
          <div class="page-graduation__intro-content editorial-content">
            <?php echo wp_kses_post($introduction); ?>
            <?php if (!empty($curriculum)) : ?>
              <p>
                <a href="<?php echo esc_url($curriculum); ?>" class="btn-education" target="_blank" rel="noopener noreferrer">Currículo do Curso</a>
              </p>
            <?php endif; ?>
            <?php echo wp_kses_post($graduateProfile); ?>
          </div>
        </header>
      </main>
    </div>
    <section id="destaques" class="graduation-highlights">
      <?php get_template_part('template-parts/components/preview-slider', null, [
        'title' => 'Destaques do curso',
        'query_news' => $highlights,
        'show_tags' => false,
        'use_href' => false,
      ]); ?>
    </section>
    <div class="container page-graduation__layout">
      <aside class="page-graduation__sidebar page-graduation__sidebar--spacer"></aside>
      <main class="page-graduation__main">
        <section id="estagios" class="page-graduation__stages">
          <div class="page-graduation__stages-content editorial-content">
            <?php echo wp_kses_post($internship); ?>
            <p>
              <a href="<?php echo esc_url(FAGROZ_SEI_TEC); ?>" class="btn-education" target="_blank" rel="noopener noreferrer">Mais Informações</a>
            </p>
          </div>
        </section>
        <section id="contato" class="page-graduation__contact">
          <div class="page-graduation__contact-list editorial-content">
            <?php echo wp_kses_post($contact); ?>
          </div>
        </section>
      </main>
    </div>
  </section>
<?php
endwhile;

get_footer();
