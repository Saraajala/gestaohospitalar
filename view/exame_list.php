<?php
session_start();
require_once '../controller/examespacientecontroller.php';
$pdo = new PDO("mysql:host=localhost;dbname=gestaohospitalar", "root", "");
$controller = new ExamesPacienteController($pdo);

if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'medico') 
 {
    $exames = $controller->listarPorMedico($_SESSION['usuario_id']);
} else {
    $exames = $controller->listarPorPaciente($_SESSION['usuario_id']);
}

// Função para definir cor conforme a etapa
function corEtapa($etapa) {
    return match($etapa) {
        'pedido' => '#3498db',       // azul
        'coleta' => '#f1c40f',       // amarelo
        'analise' => '#e67e22',      // laranja
        'laudo_pronto' => '#2ecc71', // verde
        default => '#95a5a6',        // cinza
    };
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Exames</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: #fff; }
        tr:hover { background-color: #f1f1f1; }
        .etapa { color: #fff; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .resultado-critico { color: #fff; background: #e74c3c; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .btn-atualizar { background: #2980b9; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; }
        .btn-atualizar:hover { background: #1c5980; }
    </style>
</head>
<body>

<h2>Lista de Exames</h2>

<table>
    <tr>
       <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=='medico'): ?>
    <th>Paciente</th>
<?php endif; ?>
        <th>Exame</th>
        <th>Etapa</th>
        <th>Data do exame</th>
        <th>Resultado</th>
        <th>Resultado crítico</th>
        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=='medico'): ?>
    <th>Paciente</th>
<?php endif; ?>

    </tr>

    <?php foreach ($exames as $e): ?>
    <tr>
        <?php if ($_SESSION['perfil']=='medico'): ?>
            <td><?= $e['paciente_nome'] ?></td>
        <?php endif; ?>
        <td><?= $e['exame'] ?></td>
        <td><span class="etapa" style="background: <?= corEtapa($e['etapa']) ?>"><?= ucfirst($e['etapa']) ?></span></td>
        <td><?= date('d/m/Y', strtotime($e['data_exame'])) ?></td>
        <td><?= $e['resultado'] ?? '---' ?></td>
        <td>
            <?php if (!empty($e['resultado_critico'])): ?>
                <span class="resultado-critico"><?= $e['resultado_critico'] ?></span>
            <?php else: ?>
                ---
            <?php endif; ?>
        </td>
        <?php if ($_SESSION['perfil']=='medico'): ?>
            <td><a class="btn-atualizar" href="exame_update.php?id=<?= $e['id'] ?>">Atualizar</a></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
