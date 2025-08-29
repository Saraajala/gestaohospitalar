<?php
session_start();
require_once __DIR__ . '/../controller/ConsultasPacienteController.php';

$pdo = new PDO("mysql:host=localhost;dbname=gestaohospitalar", "root", "");
$controller = new ConsultasPacienteController($pdo);

// paciente logado
$paciente_id = $_SESSION['usuario_id'] ?? null;
$consultas = $controller->listarPorPaciente($paciente_id);
?>

<h2>📋 Minhas Consultas</h2>

<?php if(!empty($consultas)): ?>
<table border="1">
    <tr>
        <th>ID</th><th>Médico</th><th>Data / Hora</th><th>Status</th>
    </tr>
    <?php foreach($consultas as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= $c['medico_nome'] ?></td>
        <td><?= $c['data_hora'] ?></td>
        <td><?= $c['status'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>Você ainda não possui consultas agendadas.</p>
<?php endif; ?>
