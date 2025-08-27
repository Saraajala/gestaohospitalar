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
$msg = '';

try {
    $usuario = $controller->mostrarPerfil($usuario_id);
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Atualização dos dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $sexo = $_POST['sexo'];

    // Atualiza dados básicos
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, telefone = ?, sexo = ? WHERE id = ?");
    $stmt->execute([$nome, $telefone, $sexo, $usuario_id]);

    // Atualiza dados específicos
    if ($usuario['tipo'] === 'médico') {
        $area = $_POST['area_de_atuacao'];
        $crm = $_POST['crm'];
        $stmt = $pdo->prepare("UPDATE medicos SET area_de_atuacao = ?, crm = ? WHERE usuario_id = ?");
        $stmt->execute([$area, $crm, $usuario_id]);
    }

    if ($usuario['tipo'] === 'paciente') {
        $data_nasc = $_POST['data_nascimento'];
        $plano = $_POST['plano_saude'];
        $alergias = $_POST['alergias'];
        $obs = $_POST['observacoes'];
        $stmt = $pdo->prepare("UPDATE pacientes SET data_nascimento = ?, plano_saude = ?, alergias = ?, observacoes = ? WHERE usuario_id = ?");
        $stmt->execute([$data_nasc, $plano, $alergias, $obs, $usuario_id]);
    }

    $msg = "Perfil atualizado com sucesso!";
    // Atualiza o usuário após salvar
    $usuario = $controller->mostrarPerfil($usuario_id);
}
?>

<h1>Editar Perfil</h1>
<?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>" required><br>

    <label>Sexo:</label><br>
    <input type="radio" name="sexo" value="M" <?= $usuario['sexo']=='M' ? 'checked' : '' ?>> Masculino
    <input type="radio" name="sexo" value="F" <?= $usuario['sexo']=='F' ? 'checked' : '' ?>> Feminino<br>

    <?php if ($usuario['tipo'] === 'médico'): ?>
        <label>Área de Atuação:</label><br>
        <input type="text" name="area_de_atuacao" value="<?= htmlspecialchars($usuario['area_de_atuacao']) ?>" required><br>
        <label>CRM:</label><br>
        <input type="text" name="crm" value="<?= htmlspecialchars($usuario['crm']) ?>" required><br>
    <?php endif; ?>

    <?php if ($usuario['tipo'] === 'paciente'): ?>
        <label>Data de Nascimento:</label><br>
        <input type="date" name="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento']) ?>"><br>
        <label>Plano de Saúde:</label><br>
        <input type="text" name="plano_saude" value="<?= htmlspecialchars($usuario['plano_saude']) ?>"><br>
        <label>Alergias:</label><br>
        <textarea name="alergias"><?= htmlspecialchars($usuario['alergias']) ?></textarea><br>
        <label>Observações:</label><br>
        <textarea name="observacoes"><?= htmlspecialchars($usuario['observacoes']) ?></textarea><br>
    <?php endif; ?>

    <br>
    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="perfil.php">Voltar ao Perfil</a>
