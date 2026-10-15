<?php

require_once get_theme_file_path('/inc/queries/repo-publicacoes-grupos-joolma.php');

function render_publicacoes_grupo_list(array $publicacoes): string
{
  if (empty($publicacoes)) {
    return '<p>Nenhuma publicação encontrada.</p>';
  }

  ob_start();
?>
  <div class="publicacoes-grupo">
    <p>
      <strong>
        TOTAL:
        <span><?= esc_html(count($publicacoes)); ?></span>
      </strong>
    </p>

    <?php $ano_atual = null; ?>

    <?php foreach ($publicacoes as $publicacao) : ?>
      <?php
      $ano = (string) $publicacao->TeseAno;

      if ($ano_atual !== $ano) :
        if ($ano_atual !== null) {
          echo '</ul>';
        }

        $ano_atual = $ano;
      ?>
        <h4 class="publicacoes-grupo__ano">
          <?= esc_html($ano_atual); ?>
        </h4>
        <ul class="publicacoes-grupo__lista">
        <?php endif; ?>

        <?php
        $autores = strtoupper(trim((string) $publicacao->TeseAluno));
        $titulo = (string) $publicacao->TeseTitulo;
        $anexo = trim((string) $publicacao->TeseAnexa);
        $identifica = (string) $publicacao->Identifica;
        $chave_primaria = (int) $publicacao->chavePrimaria;
        $titulo_tem_link = stripos($titulo, '<a') !== false || stripos($titulo, 'href=') !== false;
        $identifica_tem_link = stripos($identifica, '<a') !== false || stripos($identifica, 'href=') !== false;
        $identifica_formatado = $chave_primaria > 39624 || $chave_primaria === 39527
          ? $identifica
          : utf8_encode($identifica);
        $estilo_link = 'color: #482200 !important; text-decoration: underline !important; text-underline-offset: 2px; text-decoration-thickness: 2px;';
        ?>

        <li
          class="publicacoes-grupo__item"
          title="<?= esc_attr($chave_primaria); ?>">
          <?php if ($autores !== '') : ?>
            <span class="publicacoes-grupo__autores">
              <?= esc_html($autores); ?>.
            </span>
          <?php endif; ?>

          <?php if ($titulo_tem_link) : ?>
            <?php
            $titulo_com_estilo = preg_replace(
              '/<a\s+/i',
              '<a style="' . esc_attr($estilo_link) . '" ',
              $titulo,
              1
            );
            ?>
            <?= wp_kses_post($titulo_com_estilo); ?>
          <?php elseif ($anexo !== '0' && $anexo !== '') : ?>
            <a
              href="<?= esc_url('https://www.ufrgs.br/fagro/' . ltrim($anexo, '/')); ?>"
              target="_blank"
              rel="noopener noreferrer"
              class="publicacoes-grupo__link"
              style="<?= esc_attr($estilo_link); ?>">
              <?= esc_html($titulo); ?>
            </a>
          <?php else : ?>
            <?= esc_html($titulo); ?>
          <?php endif; ?>

          <?php if ($identifica_formatado !== '') : ?>
            .
            <?php if ($identifica_tem_link) : ?>
              <?php
              $identifica_com_estilo = preg_replace(
                '/<a\s+/i',
                '<a style="' . esc_attr($estilo_link) . '" ',
                $identifica_formatado,
                1
              );
              ?>
              <?= wp_kses_post($identifica_com_estilo); ?>.
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

function render_publicacoes_grupo_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'grupo' => '',
      'tipo' => '',
    ],
    $atts,
    'publicacoes_grupo'
  );

  $grupo = (string) $atts['grupo'];

  if (!is_publicacao_grupo_valido($grupo)) {
    return '<p>Grupo de pesquisa inválido.</p>';
  }

  $tipo = sanitize_text_field((string) $atts['tipo']);
  $resultado = get_publicacoes_grupo($grupo, $tipo);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_publicacoes_grupo_list($resultado['items']);
}

add_shortcode('publicacoes_grupo', 'render_publicacoes_grupo_shortcode');
