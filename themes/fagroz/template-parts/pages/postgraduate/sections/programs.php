<?php
$agribusiness = get_field('agribusiness');
$soilScience = get_field('soil_science');
$phytotechnics = get_field('phytotechnics');
$zootechny = get_field('zootechny');

$agribusinessCpt = get_page_by_path('agronegocio', OBJECT, 'postgraduate');
$agribusiness_link = $agribusinessCpt ? get_permalink($agribusinessCpt->ID) : home_url('/');
$soilScienceCpt = get_page_by_path('ciencia-do-solo', OBJECT, 'postgraduate');
$soilScienceLink = $soilScienceCpt ? get_permalink($soilScienceCpt->ID) : home_url('/');
$phytotechnicsCpt = get_page_by_path('fitotecnia', OBJECT, 'postgraduate');
$phytotechnicsLink = $phytotechnicsCpt ? get_permalink($phytotechnicsCpt->ID) : home_url('/');
$zootechnyCpt = get_page_by_path('zootecnia', OBJECT, 'postgraduate');
$zootechnyLink = $zootechnyCpt ? get_permalink($zootechnyCpt->ID) : home_url('/');

if (!is_array($programs) || empty($programs)) {
  $programs = [
    [
      'title' => $agribusiness['title'] ?? 'Agronegócio',
      'description' => $agribusiness['description'] ?? 'A formação aborda produção sustentável, tecnologia e gestão, preparando profissionais para os desafios do setor agrícola.',
      'bg_photo' => $agribusiness['bg_photo'] ?? get_template_directory_uri() . '/images/Página - Pós-Graduação/agronegocio.jpg',
      'shift' => $agribusiness['shift'] ?? 'Shift Integral',
      'duration' => $agribusiness['duration'] ?? '10 Semestres',
      'link' => $agribusiness_link,
    ],
    [
      'title' => $soilScience['title'] ?? 'Ciência do Solo',
      'description' => $soilScience['description'] ?? 'Foco em solos, fertilidade e conservação, formando especialistas para atuação em pesquisa e produção agrícola.',
      'bg_photo' => $soilScience['bg_photo'] ?? get_template_directory_uri() . '/images/Página - Pós-Graduação/ciencia-do-solo.jpg',
      'shift' => $soilScience['shift'] ?? 'Turno Integral',
      'duration' => $soilScience['duration'] ?? '10 Semestres',
      'link' => $soilScienceLink,
    ],
    [
      'title' => $phytotechnics['title'] ?? 'Fitotecnia',
      'description' => $phytotechnics['description'] ?? 'Estudo de plantas cultivadas, nutrição vegetal e sistemas de produção para alimentos, fibras e energia.',
      'bg_photo' => $phytotechnics['bg_photo'] ?? get_template_directory_uri() . '/images/Página - Pós-Graduação/fitotecnia.jpg',
      'shift' => $phytotechnics['shift'] ?? 'Turno Integral',
      'duration' => $phytotechnics['duration'] ?? '10 Semestres',
      'link' => $phytotechnicsLink,
    ],
    [
      'title' => $zootechny['title'] ?? 'Zootecnia',
      'description' => $zootechny['description'] ?? 'Especialização em manejo animal, nutrição e bem-estar para produção animal sustentável e eficiente.',
      'bg_photo' => $zootechny['bg_photo'] ?? get_template_directory_uri() . '/images/Página - Pós-Graduação/zootecnia.jpg',
      'shift' => $zootechny['shift'] ?? 'Turno Integral',
      'duration' => $zootechny['duration'] ?? '10 Semestres',
      'link' => $zootechnyLink,
    ],
  ];
}
?>
<section class="postgraduate-section postgraduate-section--programs">
  <div class="container">
    <div class="postgraduate-programs__grid">
      <?php foreach ($programs as $program) : ?>
        <?php
        $card_title = $program['title'] ?? '';
        $card_description = $program['description'] ?? '';
        $card_image = $program['bg_photo'] ?? '';
        if (is_array($card_image)) {
          $card_image = $card_image['url'] ?? '';
        }
        $card_turno = $program['shift'] ?? 'Turno Integral';
        $card_duration = $program['duration'] ?? '10 Semestres';
        $card_link = !empty($program['link']) ? $program['link'] : site_url('/pos-graduacao');
        ?>
        <article class="postgraduate-card">
          <div class="postgraduate-card__image">
            <img src="<?php echo esc_url($card_image); ?>" alt="<?php echo esc_attr($card_title); ?>">
          </div>
          <div class="postgraduate-card__body">
            <div class="postgraduate-card__meta">
              <span class="postgraduate-card__label"><?php echo esc_html($card_turno); ?></span>
              <span class="postgraduate-card__label"><?php echo esc_html($card_duration); ?></span>
            </div>
            <h3 class="postgraduate-card__title"><?php echo esc_html($card_title); ?></h3>
            <p class="postgraduate-card__description"><?php echo wp_kses_post($card_description); ?></p>
            <a href="<?php echo esc_url($card_link); ?>" class="btn-postgraduate">Mais informações</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>