<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/CadastroController.php';

$controller = new CadastroController($pdo);
$cadastros = $controller->listarCadastros();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<h1>Lista de Usuários</h1>
<a href="cadastro.php">Cadastrar Novo Usuário</a>

<?php if (empty($cadastros)): ?>
    <p>Nenhum usuário cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Sexo</th>
                <th>Tipo</th>
                <th>Área de Atuação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cadastros as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                    <td><?= htmlspecialchars($usuario['sexo']) ?></td>
                    <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                    <td><?= htmlspecialchars($usuario['area_de_atuacao'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
