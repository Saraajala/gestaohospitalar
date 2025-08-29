<?php
class ConsultasPacienteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar consultas de um paciente
    public function listarPorPaciente($paciente_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, m.nome AS medico_nome
            FROM consultas_paciente c
            JOIN usuarios m ON m.id = c.medico_id
            WHERE c.paciente_id = ?
            ORDER BY c.data_hora ASC
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar consultas de um médico
    public function listarPorMedico($medico_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.usuario_id AS paciente_id
            FROM consultas_paciente c
            JOIN pacientes p ON p.id = c.paciente_id
            WHERE c.medico_id = ?
            ORDER BY c.data_hora ASC
        ");
        $stmt->execute([$medico_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Detalhar uma consulta
    public function detalhar($consulta_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM consultas_paciente WHERE id = ?");
        $stmt->execute([$consulta_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
