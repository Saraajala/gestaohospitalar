<?php
class InternacoesPacienteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarInternacoes($paciente_id) {
        $stmt = $this->pdo->prepare("
            SELECT i.id, i.leito_id, l.numero_leito, i.data_entrada, i.data_alta, i.orientacoes, i.status
            FROM internacoes i
            JOIN leitos l ON l.id = i.leito_id
            WHERE i.paciente_id = ?
            ORDER BY i.data_entrada DESC
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarEvolucoes($internacao_id) {
        $stmt = $this->pdo->prepare("
            SELECT id, descricao, data_registro
            FROM evolucoes_paciente
            WHERE internacao_id = ?
            ORDER BY data_registro DESC
        ");
        $stmt->execute([$internacao_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
