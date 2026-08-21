<?php
$acfResearch = get_field('fagroz_research');
$content = $acfResearch['content']  ?? '';
?>

<?php
$researchPage = get_page_by_path('educacao/pesquisa');
$researchUrl = $researchPage ? get_permalink($researchPage) : site_url('/educacao/pesquisa');
?>

<section id="pesquisa" class="research-section">
  <div class="container research-inner">
    <div class="research-row">
      <div class="research-text">
        <?php echo wp_kses_post($content); ?>
      </div>
      <a href="<?php echo esc_url($researchUrl); ?>" class="btn-education">Ver mais</a>
    </div>
  </div>
</section>