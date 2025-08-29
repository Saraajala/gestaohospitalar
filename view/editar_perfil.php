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

// Processa o formulário
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $areaDeAtuacao = $_POST['areaDeAtuacao'] ?? '';

    // Atualiza dados no banco
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, telefone = ?, sexo = ? WHERE id = ?");
    $stmt->execute([$nome, $telefone, $sexo, $usuario_id]);

    if ($usuario['tipo'] === 'médico' && $areaDeAtuacao) {
        $stmt2 = $pdo->prepare("UPDATE medicos SET area_de_atuacao = ? WHERE usuario_id = ?");
        $stmt2->execute([$areaDeAtuacao, $usuario_id]);
    }

    $msg = '<span style="color:green">Perfil atualizado com sucesso!</span>';

    // Recarrega os dados
    $usuario = $controller->mostrarPerfil($usuario_id);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
</head>
<body>
<h1>Editar Perfil</h1>

<?= $msg ?>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required><br><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>" required><br><br>

    <label>Sexo:</label><br>
    <input type="radio" name="sexo" value="M" <?= ($usuario['sexo'] ?? '') === 'M' ? 'checked' : '' ?>> Masculino
    <input type="radio" name="sexo" value="F" <?= ($usuario['sexo'] ?? '') === 'F' ? 'checked' : '' ?>> Feminino
    <br><br>

    <?php if ($usuario['tipo'] === 'médico'): ?>
        <label>Área de Atuação:</label><br>
        <select name="areaDeAtuacao" required>
            <option value="">Selecione a área</option>
            <?php
            $areas = ["Cardiologia","Dermatologia","Endocrinologia","Ginecologia","Neurologia","Pediatria","Psiquiatria","Ortopedia","Oftalmologia","Gastroenterologia"];
            foreach($areas as $area):
                $selected = ($usuario['area_de_atuacao'] ?? '') === $area ? 'selected' : '';
                echo "<option value=\"$area\" $selected>$area</option>";
            endforeach;
            ?>
        </select><br><br>
    <?php endif; ?>

    <button type="submit">Salvar Alterações</button>
</form>

<br>
<a href="perfil.php">Voltar ao Perfil</a>
</body>
</html>
