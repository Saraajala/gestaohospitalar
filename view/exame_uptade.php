<?php
session_start();
if ($_SESSION['perfil'] != 'medico') {
    die("Acesso negado.");
}

require_once '../controller/ExamesController.php';
$pdo = new PDO("mysql:host=localhost;dbname=gestaohospitalar", "root", "");
$controller = new ExamesController($pdo);

if (!isset($_GET['id'])) {
    die("Exame não informado.");
}

$exame = $controller->getExame($_GET['id']);

if (!$exame) {
    die("Exame não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->atualizar(
        $exame['id'],
        $_POST['etapa'],
        $_POST['resultado'] ?: null,
        $_POST['resultado_critico'] ?: null
    );
    header("Location: exame_list.php");
}
?>

<h2>Atualizar exame: <?= $exame['exame'] ?></h2>

<form method="POST">
    <label>Etapa atual:</label>
    <select name="etapa" required>
        <?php
        $etapas = ['pedido', 'coleta', 'analise', 'laudo_pronto'];
        foreach ($etapas as $et) {
            $selected = $exame['etapa'] == $et ? "selected" : "";
            echo "<option value='$et' $selected>$et</option>";
        }
        ?>
    </select>

    <label>Resultado:</label>
    <textarea name="resultado"><?= $exame['resultado'] ?></textarea>

    <label>Resultado crítico:</label>
    <input type="text" name="resultado_critico" value="<?= $exame['resultado_critico'] ?>">

    <button type="submit">Atualizar exame</button>
</form>
