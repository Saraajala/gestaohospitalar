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

// Pega todas as consultas do paciente
try {
    $consultas = $controller->listarPorPaciente($paciente_id);
} catch(PDOException $e) {
    echo "<p style='color:red;'>Erro ao listar consultas: " . $e->getMessage() . "</p>";
    exit;
}
?>

<h1>Meus Agendamentos</h1>

<?php if (empty($consultas)): ?>
    <p>Você não possui consultas agendadas.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Médico</th>
                <th>Área de Atuação</th>
                <th>Data / Hora</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultas as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['medico_nome']) ?></td>
                    <td><?= htmlspecialchars($c['area_de_atuacao']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($c['data_hora'])) ?></td>
                    <td><?= htmlspecialchars($c['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<br>
<a href="agendar_consulta.php">Agendar Nova Consulta</a>
