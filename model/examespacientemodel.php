<?php
class ExamesPacienteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lista exames de um paciente
 public function listarPorPaciente($paciente_id) {
    $sql = "SELECT e.*, u.nome AS paciente_nome
            FROM exames_paciente e
            JOIN internacoes_paciente i ON e.internacao_id = i.id
            JOIN usuarios u ON i.paciente_id = u.id
            WHERE i.paciente_id = ?
            ORDER BY e.data_registro DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$paciente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Lista exames solicitados por um médico
    public function listarPorMedico($medico_id) {
        $sql = "SELECT e.*, p.nome AS paciente_nome
                FROM exames_paciente e
                JOIN usuarios p ON e.paciente_id = p.id
                WHERE e.medico_id = ?
                ORDER BY e.data_exame DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$medico_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
