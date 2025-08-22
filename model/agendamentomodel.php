<?php
class AgendamentoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Criar novo agendamento
    public function criarAgendamento($paciente_id, $medico_id, $data_consulta)
    {
        // Verifica se o médico já tem consulta no mesmo horário
        $sql = "SELECT * FROM agendamentos WHERE medico_id = ? AND data_consulta = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$medico_id, $data_consulta]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("⚠️ Médico já possui agendamento nesse horário.");
        }

        // Inserir novo agendamento
        $sql = "INSERT INTO agendamentos (paciente_id, medico_id, data_consulta, status) VALUES (?, ?, ?, 'agendada')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$paciente_id, $medico_id, $data_consulta]);
    }

    // Listar todos os agendamentos
    public function listar()
    {
        $sql = "SELECT a.id, u1.nome AS paciente, u2.nome AS medico, a.data_consulta, a.status
                FROM agendamentos a
                JOIN usuarios u1 ON a.paciente_id = u1.id
                JOIN usuarios u2 ON a.medico_id = u2.id
                ORDER BY a.data_consulta ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alterar status do agendamento
    public function mudarStatus($id, $status)
    {
        $sql = "UPDATE agendamentos SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Histórico de um paciente
    public function historico($paciente_id)
    {
        $sql = "SELECT a.id, u2.nome AS medico, a.data_consulta, a.status, a.observacoes
                FROM agendamentos a
                JOIN usuarios u2 ON a.medico_id = u2.id
                WHERE a.paciente_id = ?
                ORDER BY a.data_consulta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
