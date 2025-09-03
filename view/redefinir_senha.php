<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\config.php';
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\controller\recuperacaocontroller.php';

$controller = new RecuperacaoController($pdo);
$token = $_GET['token'] ?? '';
$email = "";

// Buscar email pelo token
if ($token) {
    $email = $controller->buscarEmailPorToken($token);
    if (!$email) {
        die("<p style='color:red; font-weight:bold;'>Token inválido ou expirado.</p>");
    }
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirmar_senha'] ?? '';

    if ($novaSenha !== $confirmaSenha) {
        $msg = "<p style='color:red;'>As senhas não coincidem!</p>";
    } else {
        try {
            $controller->redefinirSenha($token, $novaSenha);
            $msg = "<p style='color:green;'>Senha redefinida com sucesso!</p>";
        } catch (Exception $e) {
            $msg = "<p style='color:red;'>" . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2980b9;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #1c5980;
        }
        .email {
            margin-bottom: 20px;
            font-weight: bold;
            color: #555;
        }
        .msg {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Redefinir Senha</h1>

        <?php if ($email): ?>
            <p class="email">Email: <?= htmlspecialchars($email) ?></p>
        <?php endif; ?>

        <?php if ($msg) echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST">
            <label>Nova senha</label>
            <input type="password" name="nova_senha" required>

            <label>Confirmar nova senha</label>
            <input type="password" name="confirmar_senha" required>

            <button type="submit">Redefinir senha</button>
        </form>
    </div>
</body>
</html>
