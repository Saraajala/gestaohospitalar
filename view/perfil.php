<?php
session_start();
require_once '../config.php';
require_once '../controller/PerfilController.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/index.php");
    exit;
}

$controller = new PerfilController($pdo);
$usuario_id = $_SESSION['usuario_id'];

try {
    $usuario = $controller->mostrarPerfil($usuario_id);
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        p { margin: 5px 0; }
        a { display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Perfil do Usuário</h1>

    <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
    <p><strong>Telefone:</strong> <?= htmlspecialchars($usuario['telefone']) ?></p>
    <p><strong>Sexo:</strong> <?= htmlspecialchars($usuario['sexo']) ?></p>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($usuario['tipo']) ?></p>

    <?php if ($usuario['tipo'] === 'médico'): ?>
        <p><strong>Área de Atuação:</strong> <?= htmlspecialchars($usuario['area_de_atuacao']) ?></p>
        <p><strong>CRM:</strong> <?= htmlspecialchars($usuario['crm']) ?></p>
    <?php endif; ?>

    <?php if ($usuario['tipo'] === 'paciente'): ?>
        <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($usuario['data_nascimento']) ?></p>
        <p><strong>Plano de Saúde:</strong> <?= htmlspecialchars($usuario['plano_saude']) ?></p>
        <p><strong>Alergias:</strong> <?= htmlspecialchars($usuario['alergias']) ?></p>
        <p><strong>Observações:</strong> <?= htmlspecialchars($usuario['observacoes']) ?></p>
    <?php endif; ?>

    <br>
    <a href="editar_perfil.php">Editar Perfil</a>
    <br>
    <a href="../public/index.php">Voltar ao Início</a>
</body>
</html>
