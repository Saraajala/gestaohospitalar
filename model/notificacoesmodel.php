<?php
class NotificacoesModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getNotificacoes($paciente_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM notificacoes WHERE paciente_id = ? ORDER BY data_criacao DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
