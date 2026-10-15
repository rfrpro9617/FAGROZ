<?php

function get_egressos_ppgs_permitidos(): array
{
  return [
    'Solos',
    'Agronegocios',
    'Fitotecnia',
    'Zootecnia',
  ];
}

function is_egresso_ppg_valido(string $ppg): bool
{
  return in_array(trim($ppg), get_egressos_ppgs_permitidos(), true);
}

function get_egressos(string $ppg = 'Solos'): array
{
  if (!is_egresso_ppg_valido($ppg)) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Programa de pós-graduação inválido.',
    ];
  }

  $db = get_programa_db();

  if (!$db instanceof wpdb) {
    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os egressos.',
    ];
  }

  $ppg = trim($ppg);

  $sql = $db->prepare(
    "SELECT
      nivel,
      ano,
      NomeUsu,
      CONCAT(
        SUBSTRING(data_conclusao, 7, 2), '/',
        SUBSTRING(data_conclusao, 5, 2), '/',
        SUBSTRING(data_conclusao, 1, 4)
      ) AS data_conclusao,
      programa
    FROM (
      SELECT
        'MESTRADO' AS nivel,
        LEFT(concluiuPGm, 4) AS ano,
        NomeUsu,
        concluiuPGm AS data_conclusao,
        concluiuPGm AS data_conclusao_ordem,
        PGmConcluiu AS programa
      FROM programa
      WHERE concluiuPGm <> %s
        AND PGmConcluiu = %s
      UNION ALL
      SELECT
        'DOUTORADO' AS nivel,
        LEFT(concluiuPGd, 4) AS ano,
        NomeUsu,
        concluiuPGd AS data_conclusao,
        concluiuPGd AS data_conclusao_ordem,
        PGdConcluiu AS programa
      FROM programa
      WHERE concluiuPGd <> %s
        AND PGdConcluiu = %s
    ) AS formacoes
    ORDER BY nivel DESC, ano DESC, data_conclusao_ordem DESC, NomeUsu ASC",
    '0',
    $ppg,
    '0',
    $ppg
  );

  $resultados = $db->get_results($sql);

  if (!empty($db->last_error)) {
    error_log('Erro ao buscar egressos: ' . $db->last_error);

    return [
      'items' => [],
      'error' => true,
      'message' => 'Não foi possível carregar os egressos.',
    ];
  }

  return [
    'items' => $resultados ?: [],
    'error' => false,
    'message' => '',
  ];
}

function render_egressos_list(array $egressos): string
{
  if (empty($egressos)) {
    return '<p>Nenhum egresso encontrado.</p>';
  }

  $agrupado = [
    'MESTRADO' => [],
    'DOUTORADO' => [],
  ];

  foreach ($egressos as $egresso) {
    $nivel = (string) ($egresso->nivel ?? '');

    if ($nivel === '') {
      continue;
    }

    if (!isset($agrupado[$nivel])) {
      $agrupado[$nivel] = [];
    }

    $agrupado[$nivel][] = $egresso;
  }

  ob_start();
?>
  <div class="egressos">
    <p>
      <strong>
        TOTAL:
        <span><?= esc_html(count($egressos)); ?></span>
      </strong>
    </p>

    <div class="egressos__grid" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 2rem;">
      <?php foreach (['MESTRADO', 'DOUTORADO'] as $nivel) : ?>
        <?php $lista = $agrupado[$nivel] ?? []; ?>
        <div class="egressos__col">
          <h4 class="egressos__titulo" style="margin-bottom: 0.75rem;">
            <?= esc_html($nivel); ?>:
            <span><?= esc_html(count($lista)); ?></span>
          </h4>

          <?php $ano_atual = null; ?>

          <?php foreach ($lista as $item) : ?>
            <?php
            $ano = (string) ($item->ano ?? '');
            $nome = trim((string) ($item->NomeUsu ?? ''));
            $data = trim((string) ($item->data_conclusao ?? ''));
            $data_formatada = $data;
            ?>

            <?php if ($ano_atual !== $ano) : ?>
              <?php if ($ano_atual !== null) : ?>
                <br>
              <?php endif; ?>
              <div class="egressos__ano" style="font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.25rem;">
                <?= esc_html($ano); ?>
              </div>
              <?php $ano_atual = $ano; ?>
            <?php endif; ?>

            <?php if ($nome !== '') : ?>
              <div class="egressos__nome" style="margin-bottom: 0.2rem;">
                <?= esc_html($nome); ?>
                <?php if ($data_formatada !== '') : ?>
                  - <?= esc_html($data_formatada); ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php

  return ob_get_clean();
}

function render_egressos_shortcode($atts = []): string
{
  $atts = shortcode_atts(
    [
      'ppg' => 'Solos',
    ],
    $atts,
    'egressos'
  );

  $ppg = (string) $atts['ppg'];

  if (!is_egresso_ppg_valido($ppg)) {
    return '<p>Programa de pós-graduação inválido.</p>';
  }

  $resultado = get_egressos($ppg);

  if (!empty($resultado['error'])) {
    return '<p>' . esc_html($resultado['message']) . '</p>';
  }

  return render_egressos_list($resultado['items']);
}

add_shortcode('egressos', 'render_egressos_shortcode');
