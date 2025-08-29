<?php
require_once __DIR__ . '/../model/LaudosModel.php';

class LaudosController {
    private $model;

    public function __construct($pdo) {
        $this->model = new LaudosModel($pdo);
    }

    public function listarLaudos($paciente_id) {
        return $this->model->getLaudos($paciente_id);
    }
}
