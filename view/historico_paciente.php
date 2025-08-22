<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico do Paciente</title>
    <style>
        label { display: block; margin-top: 10px; }
        select, textarea { padding: 5px; width: 300px; }
        textarea { height: 80px; }
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

    // Busca pacientes do banco
    $pacientes = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo='paciente' ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Pega o paciente selecionado
    $paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;

    // Inserir nova observação
    if (isset($_POST['paciente_id'], $_POST['observacoes']) && !empty($_POST['observacoes'])) {
        $stmt = $pdo->prepare("UPDATE agendamentos SET observacoes = ? WHERE id = ?");
        $stmt->execute([$_POST['observacoes'], $_POST['agendamento_id']]);
        echo "<p>Observação adicionada com sucesso!</p>";
    }

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
                <th>ID</th>
                <th>Médico</th>
                <th>Data e Hora</th>
                <th>Status</th>
                <th>Observações</th>
                <th>Adicionar Observação</th>
            </tr>
            <?php foreach ($historico as $h): ?>
                <tr>
                    <td><?= (int)$h['id'] ?></td>
                    <td><?= htmlspecialchars($h['medico']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($h['data_consulta'])) ?></td>
                    <td><?= ucfirst($h['status']) ?></td>
                    <td><?= htmlspecialchars($h['observacoes']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="agendamento_id" value="<?= (int)$h['id'] ?>">
                            <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
                            <textarea name="observacoes" placeholder="Nova observação" required></textarea>
                            <button type="submit">Adicionar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif($paciente_id > 0): ?>
        <p>Não há consultas registradas para este paciente.</p>
    <?php endif; ?>

    <br><a href="listar_agendamentos.php">Voltar</a>
</body>
</html>
