<?php
require_once __DIR__ . '/../model/ConsultasPacienteModel.php';

class ConsultasPacienteController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ConsultasPacienteModel($pdo);
    }

    // Listar consultas de um paciente
    public function listarPorPaciente($paciente_id) {
        return $this->model->listarPorPaciente($paciente_id);
    }

    // Listar consultas de um médico
    public function listarPorMedico($medico_id) {
        return $this->model->listarPorMedico($medico_id);
    }

    // Detalhar consulta
    public function detalhar($consulta_id) {
        return $this->model->detalhar($consulta_id);
    }
}
