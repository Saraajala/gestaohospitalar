<?php
session_start();
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/ConsultasPacienteController.php';

$controller = new ConsultasPacienteController($pdo);

// paciente logado
$paciente_id = $_SESSION['usuario_id'] ?? null;
if (!$paciente_id) {
    echo "<p style='color:red;'>Você precisa estar logado como paciente!</p>";
    exit;
}

// Busca todos os médicos
try {
    $stmt = $pdo->prepare("SELECT id, nome, area_de_atuacao FROM usuarios WHERE tipo = 'médico'");
    $stmt->execute();
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar médicos: " . $e->getMessage() . "</p>";
    exit;
}

// Processa o formulário
if (isset($_POST["medico_id"], $_POST["data_hora"])) {
    $medico_id = $_POST["medico_id"];
    $data_hora = $_POST["data_hora"];

    // Verifica se o médico existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE id = ? AND tipo = 'médico'");
    $stmt->execute([$medico_id]);
    if ($stmt->fetchColumn() == 0) {
        echo "<p style='color:red;'>Médico inválido!</p>";
    } else {
        // Agenda a consulta usando o controller
        $resultado = $controller->agendar($paciente_id, $medico_id, $data_hora);
        if ($resultado === true) {
            echo "<p style='color:green;'>Consulta agendada com sucesso!</p>";
        } else {
            echo "<p style='color:red;'>$resultado</p>";
        }
    }
}
?>

<h1>Agendar Consulta</h1>
<form method="POST">
    <label>Médico:</label>
    <select name="medico_id" required>
        <option value="">Selecione o médico</option>
        <?php foreach ($medicos as $m): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?> (<?= htmlspecialchars($m['area_de_atuacao']) ?>)</option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Data / Hora:</label>
    <input type="datetime-local" name="data_hora" required>
    <br><br>

    <button type="submit">Agendar</button>
</form>

<br>
<a href="listar_agendam_
