<?php
session_start();
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/CadastroController.php';

$controller = new CadastroController($pdo);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST["tipo"] ?? '';
    $sexo = $_POST["sexo"] ?? '';

    try {
        if ($tipo === "médico") {
            $usuario_id = $controller->criarCadastro(
                $_POST["nome"],
                $_POST["email"],
                $_POST["telefone"],
                $sexo,
                $_POST["areaDeAtuacao"],
                $_POST["senha"],
                $tipo
            );
        } elseif ($tipo === "paciente") {
            $usuario_id = $controller->criarCadastro(
                $_POST["nome"],
                $_POST["email"],
                $_POST["telefone"],
                $sexo,
                null,
                $_POST["senha"],
                $tipo
            );
        }

        $_SESSION['usuario_id'] = $usuario_id;
        header("Location: ../public/index.php");
        exit;

    } catch (Exception $e) {
        $msg = '<span style="color:red">' . htmlspecialchars($e->getMessage()) . '</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
<h1>Cadastrar Usuário</h1>

<?= $msg ?>

<form method="POST">
    <label>Escolha o tipo de usuário:</label><br>
    <button type="button" onclick="selecionarTipo('médico')">Médico</button>
    <button type="button" onclick="selecionarTipo('paciente')">Paciente</button>
    <br><br>

    <input type="hidden" name="tipo" id="tipo">

    <div id="camposFormulario" style="display:block;">
        <input type="text" name="nome" placeholder="Nome" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="text" name="telefone" placeholder="Telefone" required><br><br>

        <label>Sexo</label><br>
        <input type="radio" name="sexo" value="M" id="sexo_m" checked>
        <label for="sexo_m">Masculino</label>
        <input type="radio" name="sexo" value="F" id="sexo_f">
        <label for="sexo_f">Feminino</label><br><br>

        <input type="password" name="senha" placeholder="Senha" required><br><br>

        <!-- Campo específico para médicos -->
        <div id="campoMedico" style="display:none;">
            <label for="areaDeAtuacao">Área de Atuação:</label><br>
            <select name="areaDeAtuacao">
                <option value="">Selecione a área</option>
                <option value="Cardiologia">Cardiologia</option>
                <option value="Dermatologia">Dermatologia</option>
                <option value="Endocrinologia">Endocrinologia</option>
                <option value="Ginecologia">Ginecologia</option>
                <option value="Neurologia">Neurologia</option>
                <option value="Pediatria">Pediatria</option>
                <option value="Psiquiatria">Psiquiatria</option>
                <option value="Ortopedia">Ortopedia</option>
                <option value="Oftalmologia">Oftalmologia</option>
                <option value="Gastroenterologia">Gastroenterologia</option>
            </select><br><br>
        </div>

        <button type="submit">Cadastrar</button>
    </div>
</form>

<script>
function selecionarTipo(tipo) {
    document.getElementById('tipo').value = tipo;
    document.getElementById('campoMedico').style.display = (tipo === 'médico') ? 'block' : 'none';
}
</script>
</body>
</html>
