<?php
class ConsultasPacienteModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar consultas de um paciente
    public function listarPorPaciente($paciente_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, 
                   m.nome AS medico_nome,
                   m.area_de_atuacao
            FROM consultas_paciente c
            JOIN usuarios m ON m.id = c.medico_id
            WHERE c.paciente_id = ?
            ORDER BY c.data_hora ASC
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar consultas de um médico
  // Listar consultas de um médico
public function listarPorMedico($medico_id) {
    $stmt = $this->pdo->prepare("
        SELECT c.*, 
               u.nome AS paciente_nome,
               m.nome AS medico_nome
        FROM consultas_paciente c
        JOIN usuarios u ON u.id = c.paciente_id
        JOIN usuarios m ON m.id = c.medico_id
        WHERE c.medico_id = ?
        ORDER BY c.data_hora ASC
    ");
    $stmt->execute([$medico_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Detalhar uma consulta
    public function detalhar($consulta_id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, 
                   m.nome AS medico_nome, m.area_de_atuacao,
                   u.nome AS paciente_nome
            FROM consultas_paciente c
            JOIN usuarios m ON m.id = c.medico_id
            JOIN usuarios u ON u.id = c.paciente_id
            WHERE c.id = ?
        ");
        $stmt->execute([$consulta_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Agendar uma nova consulta
    public function agendar($paciente_id, $medico_id, $data_hora) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO consultas_paciente (paciente_id, medico_id, data_hora, status)
                VALUES (?, ?, ?, 'Agendado')
            ");
            $stmt->execute([$paciente_id, $medico_id, $data_hora]);
            return true;
        } catch(PDOException $e) {
            return "Erro ao agendar consulta: " . $e->getMessage();
        }
    }
}
