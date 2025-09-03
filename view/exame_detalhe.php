<?php
require_once '../config.php';
require_once '../controller/ExamesPacienteController.php';

$controller = new ExamesPacienteController($pdo);

// SALVAR RESULTADO
if (isset($_POST['salvar_resultado']) && isset($_POST['id'])) {
    $resultado = $_POST['resultado'];
    $critico = isset($_POST['critico']) && $_POST['critico'] == '1' ? 'sim' : 'nao';

    $controller->atualizarResultado($_POST['id'], $resultado, $critico);

    header("Location: exame_detalhe.php?id=" . $_POST['id']);
    exit();
}

// DETALHAR EXAME
$exame_id = $_GET['id'] ?? null;
$exame = $exame_id ? $controller->detalhar($exame_id) : null;

// Definir etapas
$etapas = ['analise' => 'Análise', 'coleta' => 'Coleta', 'realizado' => 'Laudo Pronto'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhe do Exame</title>
    <style>
        .etapas { display:flex; gap:20px; margin-bottom:20px; }
        .etapa { padding:10px 20px; border-radius:5px; color:#fff; font-weight:bold; }
        .concluido { background-color: #4CAF50; } /* verde */
        .atual { background-color: #FFC107; }    /* amarelo */
        .critico { background-color: #f44336; }  /* vermelho */
    </style>
</head>
<body>

<?php if ($exame): ?>
<h2>🔍 Detalhes do Exame</h2>
<p><b>Paciente:</b> <?= htmlspecialchars($exame['paciente_nome'] ?? '-') ?></p>
<p><b>Médico Solicitante:</b> <?= htmlspecialchars($exame['solicitante_nome'] ?? '-') ?></p>
<p><b>Exame:</b> <?= htmlspecialchars($exame['exame']) ?></p>
<p><b>Data:</b> <?= htmlspecialchars(date('d/m/Y', strtotime($exame['data_exame'] ?? ''))) ?></p>

<!-- ETAPAS -->
<div class="etapas">
    <?php foreach($etapas as $key => $label): 
        $classe = '';
        if ($key === $exame['etapa']) $classe = 'atual';
        elseif ($key === 'realizado' && $exame['resultado_critico'] === 'sim') $classe = 'critico';
        elseif (array_search($key, array_keys($etapas)) < array_search($exame['etapa'], array_keys($etapas))) $classe = 'concluido';
    ?>
        <div class="etapa <?= $classe ?>"><?= $label ?></div>
    <?php endforeach; ?>
</div>

<!-- RESULTADO -->
<?php if ($exame['etapa'] != 'realizado'): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $exame['id'] ?>">
        <label>Resultado:</label><br>
        <textarea name="resultado" placeholder="Digite o resultado" required><?= htmlspecialchars($exame['resultado'] ?? '') ?></textarea><br><br>
        <label>
            <input type="checkbox" name="critico" value="1" <?= ($exame['resultado_critico'] ?? 'nao') === 'sim' ? 'checked' : '' ?>>
            Resultado Crítico
        </label><br><br>
        <button type="submit" name="salvar_resultado">Salvar Resultado</button>
    </form>
<?php else: ?>
    <p><b>Resultado:</b> <?= htmlspecialchars($exame['resultado']) ?></p>
    <p><b>Crítico:</b> <?= ($exame['resultado_critico'] ?? 'nao') === 'sim' ? 'Sim ⚠️' : 'Não' ?></p>
<?php endif; ?>

<a href="exame_form.php">⬅ Voltar</a>

<?php else: ?>
<p>Exame não encontrado.</p>
<a href="exame_form.php">⬅ Voltar</a>
<?php endif; ?>

</body>
</html>
