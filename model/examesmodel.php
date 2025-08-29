<?php
class ExamesModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Criar exame (pedido pelo médico)
    public function criar($dados) {
        $stmt = $this->pdo->prepare("
            INSERT INTO exames_paciente 
                (paciente_id, internacao_id, exame, solicitante, data_exame, etapa) 
            VALUES (?, ?, ?, ?, ?, 'pedido')
        ");
        return $stmt->execute([
            $dados['paciente_id'],
            $dados['internacao_id'] ?? null,
            $dados['exame'],
            $dados['solicitante'],
            $dados['data_exame']
        ]);
    }

    // Listar exames de um paciente
    public function listarPorPaciente($paciente_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM exames_paciente
            WHERE paciente_id = ?
            ORDER BY data_exame DESC
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Listar exames solicitados por um médico
public function listarPorMedico($medico_id) {
    $stmt = $this->pdo->prepare("
        SELECT e.*, u.nome AS paciente_nome
        FROM exames_paciente e
        JOIN pacientes p ON p.id = e.paciente_id
        JOIN usuarios u ON u.id = p.usuario_id
        WHERE e.solicitante = ?
        ORDER BY e.data_exame DESC
    ");
    $stmt->execute([$medico_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    // Detalhar exame
    public function detalhar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM exames_paciente WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar etapa do exame
    public function atualizarEtapa($id, $etapa) {
        $stmt = $this->pdo->prepare("
            UPDATE exames_paciente SET etapa=? WHERE id=?
        ");
        return $stmt->execute([$etapa, $id]);
    }

    // Salvar resultado
    public function salvarResultado($id, $resultado, $resultado_critico = 0) {
        $stmt = $this->pdo->prepare("
            UPDATE exames_paciente 
            SET resultado=?, resultado_critico=?, etapa='laudo pronto'
            WHERE id=?
        ");
        return $stmt->execute([$resultado, $resultado_critico, $id]);
    }
}
