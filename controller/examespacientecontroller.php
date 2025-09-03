<?php
require_once __DIR__ . '/../model/ExamesPacienteModel.php';


class ExamesPacienteController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ExamesPacienteModel($pdo);
    }

    public function listarPorPaciente($paciente_id) {
        return $this->model->listarPorPaciente($paciente_id);
    }

    public function listarPorMedico($medico_id) {
        return $this->model->listarPorMedico($medico_id);
    }
}
