<?php
require_once '../config.php';

$stmt = $pdo->query("SELECT id, status FROM leitos");
$leitos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($leitos);
