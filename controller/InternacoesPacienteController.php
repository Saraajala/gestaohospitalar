<?php
require_once __DIR__ . '/../model/InternacoesPacienteModel.php';

class InternacoesPacienteController {
    private $model;

    public function __construct($pdo) {
        $this->model = new InternacoesPacienteModel($pdo);
    }

    public function listar($paciente_id) {
        return $this->model->listarInternacoes($paciente_id);
    }

    public function listarEvolucoes($internacao_id) {
        return $this->model->listarEvolucoes($internacao_id);
    }
}
