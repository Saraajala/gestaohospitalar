<?php
require_once __DIR__ . '/../model/examesmodel.php';

class ExameController {
    private $exameModel;

    public function __construct($pdo) {
        $this->exameModel = new Exame($pdo);
    }

    // Retorna todos os exames
    public function listar() {
        return $this->exameModel->listar();
    }

    // Formulário de novo exame
    public function criar() {
        include __DIR__ . '/../view/exame_form.php';
    }

    // Salvar novo exame
    public function salvar($dados) {
        $this->exameModel->salvar($dados);
    }

    // Detalhes do exame
    public function detalhar($id) {
        return $this->exameModel->detalhar($id);
    }

    // Atualizar etapa do exame (coleta, análise, laudo)
    public function atualizarEtapa($id, $etapa) {
        $this->exameModel->atualizarEtapa($id, $etapa);
    }

    // Salvar resultado do exame
    public function salvarResultado($id, $dados) {
        $this->exameModel->salvarResultado($id, $dados);
    }
}
