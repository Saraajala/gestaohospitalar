<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Consulta</title>
</head>
<body>
    <h1>Agendar Consulta</h1>

    <?php
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
    require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/AgendamentoController.php';

    $controller = new AgendamentoController($pdo);

    // Busca todos os médicos
    $medicos = $pdo->query("SELECT id, nome, area_de_atuacao FROM usuarios WHERE tipo = 'médico'")->fetchAll(PDO::FETCH_ASSOC);

    // Busca todos os pacientes
    $pacientes = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo = 'paciente'")->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST["paciente_id"], $_POST["medico_id"], $_POST["data"])) {
        $controller->agendar($_POST["paciente_id"], $_POST["medico_id"], $_POST["data"]);
    }
    ?>

    <form method="POST">
        <label>Paciente:</label>
        <select name="paciente_id" required>
            <option value="">Selecione o paciente</option>
            <?php foreach ($pacientes as $p): ?>
                <option value="<?= $p['id'] ?>" 
                    <?= isset($_GET['paciente_id']) && $_GET['paciente_id'] == $p['id'] ? 'selected' : '' ?>>
                    <?= $p['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Médico:</label>
        <select name="medico_id" required>
            <option value="">Selecione o médico</option>
            <?php foreach ($medicos as $m): ?>
                <option value="<?= $m['id'] ?>">
                    <?= $m['nome'] ?> (<?= $m['area_de_atuacao'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Data e Hora:</label>
        <input type="datetime-local" name="data" required>
        <br><br>

        <button type="submit">Agendar</button>
    </form>

    <br><a href="listar_agendamentos.php">Ver Agendamentos</a>
</body>
</html>
