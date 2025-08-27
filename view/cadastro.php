<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
</head>
<body>
<h1>Cadastrar Usuário</h1>

<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/CadastroController.php';

$controller = new CadastroController($pdo);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST["tipo"] ?? '';
    try {
        if ($tipo === "médico") {
            $controller->criarCadastro(
                $_POST["nome"],
                $_POST["email"],
                $_POST["telefone"],
                $_POST["sexo"],
                $_POST["areaDeAtuacao"],
                $_POST["senha"],
                $tipo
            );
        } elseif ($tipo === "paciente") {
            $controller->criarCadastro(
                $_POST["nome"],
                $_POST["email"],
                $_POST["telefone"],
                $_POST["sexo"],
                null,
                $_POST["senha"],
                $tipo
            );
        }

        // Cadastro realizado com sucesso: redireciona para index.php
       header("Location: ../public/index.php");
        exit; // Para garantir que o script pare aqui

    } catch (Exception $e) {
        $msg = '<span class="mensagem erro">' . htmlspecialchars($e->getMessage()) . '</span>';
    }
}

echo $msg;
?>

<form method="POST">
    <label>Escolha o tipo de usuário:</label><br>
    <button type="button" onclick="selecionarTipo('médico')">Médico</button>
    <button type="button" onclick="selecionarTipo('paciente')">Paciente</button>
    <br><br>

    <input type="hidden" name="tipo" id="tipo">

    <div id="camposFormulario" style="display:none;">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefone" placeholder="Telefone" required>

        <label>Sexo</label><br>
        <input type="radio" name="sexo" value="M" id="sexo_m" checked>
        <label for="sexo_m">Masculino</label>
        <input type="radio" name="sexo" value="F" id="sexo_f">
        <label for="sexo_f">Feminino</label><br>

        <input type="password" name="senha" placeholder="Senha" required>

        <!-- Campo extra só para médicos -->
        <div id="campoMedico" style="display:none;">
            <label for="areaDeAtuacao">Área de Atuação:</label>
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
            </select>
        </div>

        <br>
        <button type="submit">Cadastrar</button>
    </div>
</form>

<br><a href="listar.php">Ver Cadastros</a>

<script>
    function selecionarTipo(tipo) {
        document.getElementById('tipo').value = tipo;
        document.getElementById('camposFormulario').style.display = 'block';
        document.getElementById('campoMedico').style.display = (tipo === 'médico') ? 'block' : 'none';
    }
</script>

<a href="esqueci_senha.php">esqueci a senha</a>
</body>
</html>
