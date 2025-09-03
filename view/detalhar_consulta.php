<?php
session_start();
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/ConsultasPacienteController.php';

$controller = new ConsultasPacienteController($pdo);

// paciente logado
$paciente_id = $_SESSION['usuario_id'] ?? null;
if (!$paciente_id) {
    echo "Você precisa estar logado!";
    exit;
}

// ID da consulta
$consulta_id = $_GET['id'] ?? null;
if (!$consulta_id) {
    echo "Consulta não especificada.";
    exit;
}

// Busca a consulta
$consulta = $controller->detalhar($consulta_id);

// Verifica se pertence ao paciente logado
if ($consulta['paciente_id'] != $paciente_id) {
    echo "Você não tem permissão para ver esta consulta.";
    exit;
}
?>

<h1>Detalhes da Consulta</h1>

<?php if ($consulta): ?>
<table border="1">
    <tr><th>ID da Consulta</th><td><?= $consulta['id'] ?></td></tr>
    <tr><th>Paciente</th><td><?= htmlspecialchars($consulta['paciente_nome']) ?></td></tr>
    <tr><th>Médico</th><td><?= htmlspecialchars($consulta['medico_nome']) ?></td></tr>
    <tr><th>Especialidade</th><td><?= htmlspecialchars($consulta['area_de_atuacao']) ?></td></tr>
    <tr><th>Data / Hora</th><td><?= htmlspecialchars($consulta['data_hora']) ?></td></tr>
    <tr><th>Status</th><td><?= htmlspecialchars($consulta['status'] ?? 'Agendado') ?></td></tr>
</table>
<?php else: ?>
<p>Consulta não encontrada.</p>
<?php endif; ?>

<br><a href="listar_agendamentos_paciente.php">← Voltar</a>
