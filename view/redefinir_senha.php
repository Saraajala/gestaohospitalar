<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\config.php';
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\controller/recuperacaocontroller.php';

$controller = new RecuperacaoController($pdo);
$token = $_GET['token'] ?? '';

if (isset($_POST['nova_senha'])) {
    try {
        $controller->redefinirSenha($token, $_POST['nova_senha']);
        echo "Senha redefinida com sucesso!";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<h1>Redefinir senha</h1>
<form method="POST">
    <input type="password" name="nova_senha" placeholder="Nova senha" required>
    <button type="submit">Redefinir senha</button>
</form>
