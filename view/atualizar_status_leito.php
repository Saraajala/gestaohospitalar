<?php
require_once 'C:/Turma2/xampp/htdocs/gestaohospitalar/config.php';

if (isset($_POST['leito_id']) && isset($_POST['status'])) {
    $leito_id = $_POST['leito_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE leitos SET status=? WHERE id=?");
    if($stmt->execute([$status, $leito_id])){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false]);
    }
} else {
    echo json_encode(['success'=>false, 'error'=>'Dados incompletos']);
}
