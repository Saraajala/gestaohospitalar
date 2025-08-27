<?php
require_once '../config.php';

// Libera leitos que estão em limpeza há mais de 10 segundos
$stmt = $pdo->prepare("
    UPDATE leitos 
    SET status='livre', data_limpeza=NULL
    WHERE status='limpeza' AND data_limpeza <= NOW() - INTERVAL 10 SECOND
");
$stmt->execute();
