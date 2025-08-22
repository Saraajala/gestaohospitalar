<?php
class RelatoriosModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Buscar especialidades
    public function getEspecialidades()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT area_de_atuacao FROM usuarios WHERE tipo='médico' ORDER BY area_de_atuacao ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Buscar relatórios de consultas
    public function getRelatorioConsultas($data_inicio = null, $data_fim = null, $especialidade = null)
    {
        $sql = "SELECT a.id, p.nome AS paciente, m.nome AS medico, m.area_de_atuacao, a.data_consulta, a.status 
                FROM agendamentos a
                JOIN usuarios p ON a.paciente_id = p.id
                JOIN usuarios m ON a.medico_id = m.id
                WHERE 1=1";

        $params = [];

        if ($data_inicio) {
            $sql .= " AND a.data_consulta >= ?";
            $params[] = $data_inicio . " 00:00:00";
        }

        if ($data_fim) {
            $sql .= " AND a.data_consulta <= ?";
            $params[] = $data_fim . " 23:59:59";
        }

        if ($especialidade) {
            $sql .= " AND m.area_de_atuacao = ?";
            $params[] = $especialidade;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
