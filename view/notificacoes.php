<?php
session_start();
require_once '../config.php';
require_once '../controller/NotificacoesController.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit;
}

$paciente_id = $_SESSION['usuario_id'];
$controller = new NotificacoesController($pdo);
$notificacoes = $controller->listarNotificacoes($paciente_id);
?>

<h1>Notificações</h1>
<?php if ($notificacoes): ?>
    <ul>
        <?php foreach ($notificacoes as $notif): ?>
            <li>
                <?= date("d/m/Y H:i", strtotime($notif['data_criacao'])) ?> - <?= htmlspecialchars($notif['mensagem']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Sem notificações no momento.</p>
<?php endif; ?>
<a href="../public/index.php">Voltar ao Painel</a>
