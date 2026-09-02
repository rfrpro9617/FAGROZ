<?php
$consuni_page = get_page_by_path('administracao/consuni');
$consuni_page_url = $consuni_page ? get_permalink($consuni_page) : site_url('/administracao/consuni');
?>

<section class="consuni" aria-labelledby="consuni-title">
  <div class="container">
    <div class="consuni__content">
      <header class="consuni__header">
        <h2 id="consuni-title" class="consuni__title">CONSUNI</h2>
        <p class="consuni__description">
          O Conselho da Unidade (CONSUNI), órgão de administração superior da FAGRO, tem funções consultiva, propositiva, deliberativa, normativa e de planejamento e supervisão das atividades de ensino, pesquisa e extensão. Suas competências estão estabelecidas no Art. 9º do Regimento da Faculdade de Agronomia.
        </p>
      </header>

      <div class="consuni__toolbar">
        <h3 class="consuni__subtitle">
          <a href="<?php echo esc_url($consuni_page_url); ?>">Conheça o conselho</a>
        </h3>
      </div>
    </div>
  </div>
</section>