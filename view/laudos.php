<?php
session_start();
require_once '../config.php';
require_once '../controller/LaudosController.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit;
}

$paciente_id = $_SESSION['usuario_id'];
$controller = new LaudosController($pdo);
$laudos = $controller->listarLaudos($paciente_id);
?>

<h1>Meus Laudos</h1>
<?php if ($laudos): ?>
    <ul>
        <?php foreach ($laudos as $laudo): ?>
            <li>
                <?= date("d/m/Y", strtotime($laudo['criado_em'])) ?> - <?= htmlspecialchars($laudo['descricao']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Você ainda não possui laudos cadastrados.</p>
<?php endif; ?>
<a href="../public/index.php">Voltar ao Painel</a>
