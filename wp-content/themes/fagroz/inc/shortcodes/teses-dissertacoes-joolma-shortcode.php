<?php

function render_teses_dissertacoes_list(array $teses_dissertacoes): string
{
  if (empty($teses_dissertacoes)) {
    return '<p>Nenhuma tese ou dissertação encontrada.</p>';
  }

  ob_start();
?>
  <div class="teses_dissertacoes">
    <p>
      <strong>
        TOTAL:
        <span><?= esc_html(count($teses_dissertacoes)); ?></span>
      </strong>
    </p>

    <?php $ano_atual = null; ?>

    <?php foreach ($teses_dissertacoes as $tese) : ?>
      <?php
      if ($ano_atual !== $tese->TeseAno) :
        // Fechar a lista anterior, se houver, pois vem ordenado por ano decrescente do banco de dados.
        if ($ano_atual !== null) {
          echo '</ul>';
        }

        $ano_atual = $tese->TeseAno;
      ?>
        <h4 class="teses-dissertacoes__ano">
          <?= esc_html($ano_atual); ?>
        </h4>
        <ul class="teses-dissertacoes__lista">
        <?php endif; ?>

        <?php
        $autores = strtoupper(trim((string) $tese->TeseAluno));
        $titulo = (string) $tese->TeseTitulo;
        $anexo = trim((string) $tese->TeseAnexa);
        $identifica = (string) $tese->Identifica;
        $titulo_ja_tem_link = stripos($titulo, '<a') !== false || stripos($titulo, 'href=') !== false;
        $identifica_ja_tem_link = stripos($identifica, '<a') !== false || stripos($identifica, 'href=') !== false;
        ?>

        <li
          class="teses-dissertacoes__item"
          <?php if ($identifica !== '') : ?>
          title="<?= esc_attr(wp_strip_all_tags($identifica)); ?>"
          <?php endif; ?>>

          <?php if ($autores !== '') : ?>
            <span class="teses-dissertacoes__autores">
              <?= esc_html($autores); ?>.
            </span>
          <?php endif; ?>

          <?php if ($titulo_ja_tem_link) : ?>
            <?php
            $titulo_com_estilo = preg_replace(
              '/<a\s+/i',
              '<a style="color: #482200 !important; text-decoration: underline !important; text-underline-offset: 2px; text-decoration-thickness: 2px;" ',
              $titulo,
              1
            );
            ?>
            <?= $titulo_com_estilo; ?>
          <?php elseif ($anexo != '0') : ?>
            <a
              href="<?= esc_url('https://www.ufrgs.br/fagro/' . ltrim($anexo, '/')); ?>"
              target="_blank"
              rel="noopener noreferrer"
              class="teses-dissertacoes__link"
              style="color: #482200 !important; text-decoration: underline !important; text-underline-offset: 2px; text-decoration-thickness: 2px;">
              <?= esc_html($titulo); ?>
            </a>
          <?php else : ?>
            <?= esc_html($titulo); ?>
          <?php endif; ?>

          <?php
          $identifica_formatado = (int) $tese->chavePrimaria > 39624
            || (int) $tese->chavePrimaria === 39527
            ? (string) $tese->Identifica
            : utf8_encode((string) $tese->Identifica);
          ?>

          <?php if ($identifica_formatado !== '') : ?>
            <?php if ($identifica_ja_tem_link) : ?>
              <?php
              $identifica_com_estilo = preg_replace(
                '/<a\s+/i',
                '<a style="color: #482200 !important; text-decoration: underline !important; text-underline-offset: 2px; text-decoration-thickness: 2px;" ',
                $identifica_formatado,
                1
              );
              ?>
              <?= $identifica_com_estilo; ?>.
            <?php else : ?>
              <?= esc_html($identifica_formatado); ?>.
            <?php endif; ?>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>

      <?php if ($ano_atual !== null) : ?>
        </ul>
      <?php endif; ?>
  </div>
<?php

  return ob_get_clean();
}

function render_teses_dissertacoes_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'ppg' => 'Solos',
    ],
    $atts,
    'teses_dissertacoes'
  );

  $ppg = (string) $atts['ppg'];

  if (!is_ppg_valido($ppg)) {
    return '<p>Programa de pós-graduação inválido.</p>';
  }

  $resultado = get_teses_dissertacoes($ppg);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_teses_dissertacoes_list($resultado['items']);
}

add_shortcode('teses_dissertacoes', 'render_teses_dissertacoes_shortcode');
