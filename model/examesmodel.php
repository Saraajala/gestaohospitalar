<?php
require_once 'C:\Turma2\xampp\htdocs\gestaohospitalar\config.php';
class Exame {
     private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM exames ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO exames (paciente, medico_solicitante, exame, data_pedido) VALUES (?,?,?,?)");
        return $stmt->execute([$dados['paciente'], $dados['medico'], $dados['exame'], $dados['data_pedido']]);
    }

    public function atualizarEtapa($id, $etapa) {
        $stmt = $this->pdo->prepare("UPDATE exames SET etapa=? WHERE id=?");
        return $stmt->execute([$etapa, $id]);
    }

    public function detalhar($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM exames WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvarResultado($id, $resultado, $critico) {
        $stmt = $this->pdo->prepare("UPDATE exames SET resultado=?, resultado_critico=?, etapa='laudo pronto' WHERE id=?");
        return $stmt->execute([$resultado, $critico, $id]);
    }
}