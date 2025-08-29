<?php
require_once __DIR__ . '/../model/ExamesModel.php';

class ExamesController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ExamesModel($pdo);
    }

    public function criar($dados) {
        return $this->model->criar($dados);
    }

    public function listar($paciente_id) {
        return $this->model->listarPorPaciente($paciente_id);
    }

    public function listarPorMedico($medico_nome) {
    return $this->model->listarPorMedico($medico_nome);
}

    public function detalhar($id) {
        return $this->model->detalhar($id);
    }

    public function atualizarEtapa($id, $etapa) {
        return $this->model->atualizarEtapa($id, $etapa);
    }

    public function salvarResultado($id, $resultado, $resultado_critico = 0) {
        return $this->model->salvarResultado($id, $resultado, $resultado_critico);
    }
}
