<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/ExamesPacienteController.php';

// ID do paciente logado
$paciente_id = $_SESSION['usuario_id'] ?? null;

if (!$paciente_id) {
    die("Paciente não logado.");
}

$controller = new ExamesPacienteController($pdo);
$exames = $controller->listar($paciente_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Exames</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
th, td { border: 1px solid #000; padding: 8px; text-align: left; }
th { background-color: #f4f4f4; }
.critico { background-color: #fdd; } /* linha vermelha para resultado crítico */
.realizado { background-color: #dfd; } /* linha verde para exames realizados normais */
</style>
</head>
<body>
<h1>Meus Exames</h1>
<a href="../public/index.php">⬅ Voltar</a>

<table>
<tr>
    <th>ID</th>
    <th>Exame</th>
    <th>Data</th>
    <th>Resultado</th>
    <th>Etapa</th>
    <th>Solicitante</th>
</tr>

<?php if (!empty($exames)): ?>
    <?php foreach ($exames as $e): ?>
    <?php
        // Definir classe para linha
        $classe = '';
        if (($e['resultado_critico'] ?? 'nao') === 'sim') {
            $classe = 'critico';
        } elseif (($e['etapa'] ?? '') === 'realizado') {
            $classe = 'realizado';
        }
    ?>
    <tr class="<?= $classe ?>">
        <td><?= htmlspecialchars($e['id']) ?></td>
        <td><?= htmlspecialchars($e['exame']) ?></td>
        <td><?= htmlspecialchars(date('d/m/Y', strtotime($e['data_exame'] ?? $e['data_registro'] ?? ''))) ?></td>
        <td><?= nl2br(htmlspecialchars($e['resultado'] ?? '-')) ?></td>
        <td><?= htmlspecialchars($e['etapa'] ?? '-') ?></td>
        <td><?= htmlspecialchars($e['solicitante_nome'] ?? '-') ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6">Nenhum exame cadastrado</td>
    </tr>
<?php endif; ?>
</table>
</body>
</html>
