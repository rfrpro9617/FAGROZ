<?php
$title = $args['title'] ?? 'Quem compõe a FAGROZ?';
$description = $args['description'] ?? '';
$nossa_direcao = $args['nossa_direcao'] ?? '';
$docentes = $args['docentes'] ?? '';
$servidores = $args['servidores'] ?? '';
?>
<section class="section page-administracao page-administracao__quem-compoe">
  <div class="container page-administracao__layout">
    <h2 class="page-administracao__title"><?php echo esc_html($title); ?></h2>
    <?php if (!empty($description)): ?>
      <p class="page-administracao__description"><?php echo wp_kses_post($description); ?></p>
    <?php endif; ?>
    
    <div class="page-administracao__accordion-wrapper">
      <details class="page-administracao__accordion">
        <summary class="page-administracao__accordion-title">
          <span>Nossa direção</span>
          <span class="page-administracao__accordion-icon">+</span>
        </summary>
        <div class="page-administracao__accordion-content">
          <?php echo wp_kses_post($nossa_direcao); ?>
        </div>
      </details>

      <details class="page-administracao__accordion">
        <summary class="page-administracao__accordion-title">
          <span>Docentes</span>
          <span class="page-administracao__accordion-icon">+</span>
        </summary>
        <div class="page-administracao__accordion-content">
          <?php echo wp_kses_post($docentes); ?>
        </div>
      </details>

      <details class="page-administracao__accordion">
        <summary class="page-administracao__accordion-title">
          <span>Técnicos Administrativos</span>
          <span class="page-administracao__accordion-icon">+</span>
        </summary>
        <div class="page-administracao__accordion-content">
          <?php echo wp_kses_post($servidores); ?>
        </div>
      </details>
    </div>
  </div>
</section>
