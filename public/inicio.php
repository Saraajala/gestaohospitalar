<?php
session_start();

// Se o usuário já estiver logado, redireciona para index.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo - Sistema Hospitalar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        a { display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Bem-vindo ao Sistema Hospitalar</h1>
    <p>Por favor, entre no sistema ou cadastre-se para continuar:</p>

    <a href="../view/cadastro.php">Cadastrar</a><br>
    <a href="../view/login.php">Entrar</a>
</body>
</html>
