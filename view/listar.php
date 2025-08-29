<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/controller/CadastroController.php';

$controller = new CadastroController($pdo);
$usuarios = $controller->listarCadastros();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Usuários Cadastrados</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Sexo</th>
                <th>Tipo</th>
                <th>Área de Atuação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                    <td>
                        <?php
                            if ($usuario['sexo'] === 'M') echo 'Masculino';
                            elseif ($usuario['sexo'] === 'F') echo 'Feminino';
                            else echo '-';
                        ?>
                    </td>
                    <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                    <td>
                        <?= $usuario['tipo'] === 'médico' ? htmlspecialchars($usuario['area_de_atuacao']) : '-' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="cadastro.php">Cadastrar novo usuário</a>
</body>
</html>
