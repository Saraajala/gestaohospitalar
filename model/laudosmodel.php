<?php
class LaudosModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getLaudos($paciente_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM laudos WHERE paciente_id = ? ORDER BY criado_em DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
