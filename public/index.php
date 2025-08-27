<?php
session_start();

// Simulação de login: define o usuário logado
// Para testar, substitua pelo ID de um usuário existente no seu banco
$_SESSION['usuario_id'] = 1; // ID do usuário que você quer testar

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial - Teste Perfil</title>
</head>
<body>
    <h1>Bem-vindo ao Sistema de Teste</h1>

    <p>Usuário logado: ID <?= $_SESSION['usuario_id'] ?></p>

    <ul>
        <li><a href="../view/perfil.php">Ver Perfil</a></li>
        <li><a href="../view/editar_perfil.php">Editar Perfil</a></li>
        <li><a href="../view/logout.php">Sair</a></li>
    </ul>
</body>
</html>
