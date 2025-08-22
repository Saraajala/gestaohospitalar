<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico do Paciente</title>
    <style>
        label { display: block; margin-top: 10px; }
        select { padding: 5px; width: 250px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Histórico do Paciente</h1>

    <?php
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/AgendamentoController.php';

    $controller = new AgendamentoController($pdo);

    // Lista de pacientes
    $pacientes = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo='paciente' ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Paciente selecionado
    $paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;

    if ($paciente_id > 0) {
        $historico = $controller->historico($paciente_id);
        $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
        $stmt->execute([$paciente_id]);
        $paciente_nome = $stmt->fetchColumn();
        echo "<h2>Paciente: " . htmlspecialchars($paciente_nome) . "</h2>";
    }
    ?>

    <form method="GET">
        <label>Selecione o paciente:</label>
        <select name="paciente_id" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($pacientes as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $paciente_id === (int)$p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar Histórico</button>
    </form>

    <?php if (!empty($historico)): ?>
        <table>
            <tr>
                <th>Médico</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
            <?php foreach ($historico as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['medico']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($h['data_consulta'])) ?></td>
                    <td><?= ucfirst($h['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif($paciente_id > 0): ?>
        <p>Não há consultas registradas para este paciente.</p>
    <?php endif; ?>

    <br><a href="listar_agendamentos.php">Voltar</a>
</body>
</html>
