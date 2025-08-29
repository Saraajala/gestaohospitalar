<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_destroy();
    header("Location: inicio.php");
    exit;
}

$tipo = $usuario['tipo'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Página Inicial - <?= htmlspecialchars($usuario['nome']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 6px 0;
        }
    </style>
</head>

<body>

    <h1>Bem-vindo(a), <?= htmlspecialchars($usuario['nome']) ?>!</h1>
    <p><b>Tipo:</b> <?= htmlspecialchars($tipo) ?></p>

    <h2>Menu</h2>
    <ul>
        <li><a href="../view/perfil.php">Ver Perfil</a></li>
        <li><a href="../view/editar_perfil.php">Editar Perfil</a></li>

        <?php if ($tipo === 'médico'): ?>
            <!-- Funcionalidades do médico -->
            <li><a href="../view/agendar.php">Agendar Consulta</a></li>
            <li><a href="../view/internacoes.php">Gestão de Internações</a></li>
            <li><a href="../view/exame_list.php">Exames</a></li>
            <li><a href="../view/historico_paciente.php">histórico dos pacientes</a></li>
            <li><a href="../view/consultas_medico.php">Minhas Consultas</a></li>

        <?php elseif ($tipo === 'paciente'): ?>
            <!-- Funcionalidades do paciente -->

            <li><a href="../view/internacoes_paciente.php">Minhas Internações</a></li>
            <li><a href="../view/exames_paciente.php">Meus Exames</a></li>
            <li><a href="../view/laudos_paciente.php">Laudos</a></li>
            <li><a href="../view/consultas_paciente.php">Minhas Consultas</a></li>
            <li><a href="../view/notificacoes_paciente.php">Notificações</a></li>
            <li><a href="../view/agendar_consulta.php">Agendar Consulta</a></li>
        <?php endif; ?>

        <li><a href="../view/logout.php">Sair</a></li>
    </ul>

</body>

</html>