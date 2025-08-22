<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\controller\examescontroller.php';

$pdo = new PDO("mysql:host=localhost;dbname=gestaohospitalar", "root", "");
$controller = new ExameController($pdo);

// SALVAR RESULTADO
if (isset($_POST['salvar_resultado']) && isset($_POST['id'])) {
    $controller->salvarResultado($_POST['id'], $_POST);
    // Depois de salvar, volta para a lista
    header("Location: lista_exames.php");
    exit();
}

// DETALHAR EXAME
$exame = isset($_GET['id']) ? $controller->detalhar($_GET['id']) : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhe do Exame</title>
</head>
<body>
<?php if($exame): ?>
<h2>🔍 Detalhes do Exame</h2>
<p><b>Paciente:</b> <?= $exame['paciente'] ?></p>
<p><b>Médico:</b> <?= $exame['solicitante'] ?></p>
<p><b>Exame:</b> <?= $exame['exame'] ?></p>
<p><b>Data:</b> <?= $exame['data_exame'] ?></p>
<p><b>Etapa:</b> <?= $exame['etapa'] ?></p>

<?php if ($exame['etapa'] != 'laudo pronto'): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $exame['id'] ?>">
        <label>Resultado:</label><br>
        <textarea name="resultado" placeholder="Digite o resultado" required><?= $exame['resultado'] ?? '' ?></textarea><br><br>
        <label>
            <input type="checkbox" name="critico" value="1" <?= ($exame['resultado_critico'] ?? 0) ? 'checked' : '' ?>>
            Resultado Crítico
        </label><br><br>
        <button type="submit" name="salvar_resultado">Salvar Resultado</button>
    </form>
<?php else: ?>
    <p><b>Resultado:</b> <?= $exame['resultado'] ?></p>
    <p><b>Crítico:</b> <?= $exame['resultado_critico'] ? 'Sim ⚠️' : 'Não' ?></p>
<?php endif; ?>

<a href="lista_exames.php">⬅ Voltar para Lista</a>
<?php else: ?>
<p>Exame não encontrado.</p>
<a href="lista_exames.php">⬅ Voltar para Lista</a>
<?php endif; ?>
</body>
</html>
