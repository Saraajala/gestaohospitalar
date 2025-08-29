<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../controller/InternacoesPacienteController.php';

$paciente_id = $_SESSION['usuario_id'];
$controller = new InternacoesPacienteController($pdo);
$internacoes = $controller->listar($paciente_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Minhas Internações</title>
</head>
<body>
<h1>Minhas Internações</h1>
<a href="../public/index.php">⬅ Voltar</a>
<?php if(empty($internacoes)): ?>
    <p>Nenhuma internação registrada.</p>
<?php else: ?>
    <?php foreach($internacoes as $i): ?>
        <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
            <p><b>Leito:</b> <?= htmlspecialchars($i['numero_leito']) ?></p>
            <p><b>Entrada:</b> <?= date('d/m/Y H:i', strtotime($i['data_entrada'])) ?></p>
            <p><b>Alta:</b> <?= $i['data_alta'] ? date('d/m/Y H:i', strtotime($i['data_alta'])) : '-' ?></p>
            <p><b>Orientações:</b> <?= nl2br(htmlspecialchars($i['orientacoes'])) ?></p>

            <h4>Evoluções</h4>
            <?php $evolucoes = $controller->listarEvolucoes($i['id']); ?>
            <ul>
                <?php foreach($evolucoes as $ev): ?>
                    <li><?= date('d/m/Y H:i', strtotime($ev['data_registro'])) ?> — <?= nl2br(htmlspecialchars($ev['descricao'])) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
