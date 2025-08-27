<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\config.php';
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\controller/recuperacaocontroller.php';

$controller = new RecuperacaoController($pdo);
$mensagem = '';
$mostrarFormNovaSenha = false;

if (isset($_POST['acao'])) {

    // Etapa 1: Validar se o usuário existe
    if ($_POST['acao'] === 'validar') {
        $email = $_POST['email'];
        if ($controller->usuarioExiste($email)) {
            $mensagem = "Usuário encontrado. Digite sua nova senha abaixo.";
            $mostrarFormNovaSenha = true;
        } else {
            $mensagem = "Usuário não encontrado.";
        }
    }

    // Etapa 2: Redefinir senha
    if ($_POST['acao'] === 'redefinir') {
        $email = $_POST['email'];
        $novaSenha = $_POST['nova_senha'];
        try {
            $controller->redefinirSenha($email, $novaSenha);
            $mensagem = "Senha redefinida com sucesso!";
            $mostrarFormNovaSenha = false;
        } catch (Exception $e) {
            $mensagem = $e->getMessage();
        }
    }
}
?>

<h1>Recuperação de Senha</h1>
<p style="color:blue;"><?= $mensagem ?></p>

<?php if (empty($mostrarFormNovaSenha)): ?>
<!-- Formulário para digitar email -->
<form method="POST">
    <input type="hidden" name="acao" value="validar">
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <button type="submit">Continuar</button>
</form>
<?php else: ?>
<!-- Formulário para digitar nova senha -->
<form method="POST">
    <input type="hidden" name="acao" value="redefinir">
    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
    <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
    <button type="submit">Redefinir Senha</button>
</form>
<?php endif; ?>
