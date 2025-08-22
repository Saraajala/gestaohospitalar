<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\config.php';

class Exame {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Listar todos os exames
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM exames ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Salvar novo exame
    public function salvar($dados) {
        $stmt = $this->pdo->prepare("
            INSERT INTO exames (paciente, solicitante, exame, data_exame, etapa)
            VALUES (?, ?, ?, ?, 'coleta')
        ");
        return $stmt->execute([
            $dados['paciente'],
            $dados['solicitante'],
            $dados['exame'],
            $dados['data_exame']
        ]);
    }

    // Atualizar etapa do exame
    public function atualizarEtapa($id, $etapa) {
        $stmt = $this->pdo->prepare("UPDATE exames SET etapa=? WHERE id=?");
        return $stmt->execute([$etapa, $id]);
    }

    // Detalhar exame
    public function detalhar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM exames WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Salvar resultado e marcar se é crítico
    public function salvarResultado($id, $dados) {
        $critico = isset($dados['critico']) ? 1 : 0;
        $resultado = $dados['resultado'] ?? '';
        $stmt = $this->pdo->prepare("
            UPDATE exames 
            SET resultado=?, resultado_critico=?, etapa='laudo pronto' 
            WHERE id=?
        ");
        return $stmt->execute([$resultado, $critico, $id]);
    }
}
