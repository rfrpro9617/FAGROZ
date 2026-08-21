<?php
$deptFitossanidade = get_field('dept_fitossanidade');
$deptHorticulturaESilvicultura = get_field('dept_horticultura_e_silvicultura');
$deptPlantasDeLavoura = get_field('dept_plantas_de_lavoura');
$deptPlantasForrageirasEAgrometeorologia = get_field('dept_plantas_forrageiras_e_agrometeorologia');
$deptSolos = get_field('dept_solos');
$deptZootecnia = get_field('dept_zootecnia');

$deptFitossanidadeCpt = get_page_by_path('departamento-de-fitossanidade', OBJECT, 'departments');
$deptFitossanidadeLink = $deptFitossanidadeCpt ? get_permalink($deptFitossanidadeCpt->ID) : home_url('/');
$deptHorticulturaESilviculturaCpt = get_page_by_path('departamento-de-horticultura-e-silvicultura', OBJECT, 'departments');
$deptHorticulturaESilviculturaLink = $deptHorticulturaESilviculturaCpt ? get_permalink($deptHorticulturaESilviculturaCpt->ID) : home_url('/');
$deptPlantasDeLavouraCpt = get_page_by_path('departamento-de-plantas-de-lavoura', OBJECT, 'departments');
$deptPlantasDeLavouraLink = $deptPlantasDeLavouraCpt ? get_permalink($deptPlantasDeLavouraCpt->ID) : home_url('/');
$deptPlantasForrageirasEAgrometeorologiaCpt = get_page_by_path('departamento-de-plantas-forrageiras-e-agrometeorologia', OBJECT, 'departments');
$deptPlantasForrageirasEAgrometeorologiaLink = $deptPlantasForrageirasEAgrometeorologiaCpt ? get_permalink($deptPlantasForrageirasEAgrometeorologiaCpt->ID) : home_url('/');
$deptSolosCpt = get_page_by_path('departamento-de-solos', OBJECT, 'departments');
$deptSolosLink = $deptSolosCpt ? get_permalink($deptSolosCpt->ID) : home_url('/');
$deptZootecniaCpt = get_page_by_path('departamento-de-zootecnia', OBJECT, 'departments');
$deptZootecniaLink = $deptZootecniaCpt ? get_permalink($deptZootecniaCpt->ID) : home_url('/');

if (!is_array($programs) || empty($programs)) {
  $programs = [
    [
      'title' => $deptFitossanidade['title'] ?? '',
      'bg_photo' => $deptFitossanidade['bg_photo'] ?? '',
      'link' => $deptFitossanidadeLink,
      'id' => 'fitossanidade',
    ],
    [
      'title' => $deptHorticulturaESilvicultura['title'] ?? '',
      'bg_photo' => $deptHorticulturaESilvicultura['bg_photo'] ?? '',
      'link' => $deptHorticulturaESilviculturaLink,
      'id' => 'horticultura-e-silvicultura',
    ],
    [
      'title' => $deptPlantasDeLavoura['title'] ?? '',
      'bg_photo' => $deptPlantasDeLavoura['bg_photo'] ?? '',
      'link' => $deptPlantasDeLavouraLink,
      'id' => 'plantas-de-lavoura',
    ],
    [
      'title' => $deptPlantasForrageirasEAgrometeorologia['title'] ?? '',
      'bg_photo' => $deptPlantasForrageirasEAgrometeorologia['bg_photo'] ?? '',
      'link' => $deptPlantasForrageirasEAgrometeorologiaLink,
      'id' => 'plantas-forrageiras-e-agrometeorologia',
    ],
    [
      'title' => $deptSolos['title'] ?? '',
      'bg_photo' => $deptSolos['bg_photo'] ?? '',
      'link' => $deptSolosLink,
      'id' => 'solos',
    ],
    [
      'title' => $deptZootecnia['title'] ?? '',
      'bg_photo' => $deptZootecnia['bg_photo'] ?? '',
      'link' => $deptZootecniaLink,
      'id' => 'zootecnia',
    ],
  ];
}
?>
<section class="departments-section departments-section--programs">
  <div class="container">
    <div class="departments-programs__grid">
      <?php foreach ($programs as $program) : ?>
        <?php
        $card_title = $program['title'] ?? '';
        $card_image = $program['bg_photo'] ?? '';
        if (is_array($card_image)) {
          $card_image = $card_image['url'] ?? '';
        }
        $card_link = !empty($program['link']) ? $program['link'] : site_url('/pos-graduacao');
        ?>
        <article id="<?php echo esc_attr($program['id'] ?? ''); ?>" class="departments-card">
          <div class="departments-card__image">
            <img src="<?php echo esc_url($card_image); ?>" alt="<?php echo esc_attr($card_title); ?>">
          </div>
          <div class="departments-card__body">
            <h3 class="departments-card__title"><?php echo esc_html($card_title); ?></h3>
            <a href="<?php echo esc_url($card_link); ?>" class="btn-departments">
              <span class="dashicons dashicons-external"></span> Mais informações
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>