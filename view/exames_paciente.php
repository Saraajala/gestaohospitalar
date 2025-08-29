<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/ExamesPacienteController.php';

$paciente_id = $_SESSION['usuario_id'];
$controller = new ExamesPacienteController($pdo);
$exames = $controller->listar($paciente_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Exames</title>
</head>
<body>
<h1>Meus Exames</h1>
<a href="../public/index.php">⬅ Voltar</a>
<table border="1" cellpadding="5">
<tr>
    <th>ID</th><th>Exame</th><th>Data</th><th>Etapa</th><th>Resultado</th>
</tr>
<?php if(!empty($exames)): ?>
    <?php foreach($exames as $e): ?>
    <tr>
        <td><?= $e['id'] ?></td>
        <td><?= htmlspecialchars($e['exame']) ?></td>
        <td><?= date('d/m/Y', strtotime($e['data_exame'])) ?></td>
        <td><?= $e['etapa'] ?></td>
        <td><?= nl2br(htmlspecialchars($e['resultado'] ?? '')) ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr><td colspan="5">Nenhum exame cadastrado</td></tr>
<?php endif; ?>
</table>
</body>
</html>
